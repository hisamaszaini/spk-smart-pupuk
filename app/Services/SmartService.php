<?php

namespace App\Services;

use App\Models\Petani;
use App\Models\Kriteria;
use App\Models\BobotKriteria;

class SmartService
{
    /**
     * Calculate SMART rankings and fertilizer allocation.
     *
     * @param float $totalPupuk
     * @return array
     */
    public function calculate(float $totalPupuk)
    {
        $petanis = Petani::all();
        $kriterias = Kriteria::with('bobot')->get();
        
        if ($petanis->isEmpty() || $kriterias->isEmpty()) {
            return [
                'results' => [],
                'normalized_weights' => [],
                'kriterias' => [],
                'total_weight' => 0
            ];
        }

        // 1. Weight Normalization
        $totalWeight = $kriterias->sum(fn($k) => $k->bobot->nilai_bobot ?? 0);
        $normalizedWeights = [];
        foreach ($kriterias as $k) {
            $normalizedWeights[$k->id_kriteria] = ($k->bobot->nilai_bobot ?? 0) / ($totalWeight ?: 1);
        }

        // 2. Determine Max/Min for each criterion (for utility calculation)
        $metrics = $this->getMetrics($petanis, $kriterias);

        // 3. Calculate Utility & Preference Values
        $results = [];
        foreach ($petanis as $petani) {
            $totalPreference = 0;
            $utilities = [];
            $rawValues = [];

            foreach ($kriterias as $k) {
                $value = $this->getPetaniValueForKriteria($petani, $k);
                $rawValues[$k->id_kriteria] = $value;
                
                $min = $metrics[$k->id_kriteria]['min'];
                $max = $metrics[$k->id_kriteria]['max'];
                
                // Utility calculation: (Cout - Cmin) / (Cmax - Cmin)
                if ($max - $min == 0) {
                    $utility = 1;
                } else {
                    if ($k->jenis_kriteria === 'benefit') {
                        $utility = ($value - $min) / ($max - $min);
                    } else {
                        $utility = ($max - $value) / ($max - $min);
                    }
                }

                $utilities[$k->id_kriteria] = $utility;
                $totalPreference += $utility * $normalizedWeights[$k->id_kriteria];
            }

            $results[] = [
                'petani' => $petani,
                'total_preference' => $totalPreference,
                'utilities' => $utilities,
                'raw_values' => $rawValues
            ];
        }

        // 4. Ranking
        usort($results, fn($a, $b) => $b['total_preference'] <=> $a['total_preference']);

        // 5. Allocation (Proportional based on preference)
        $sumPreference = array_sum(array_column($results, 'total_preference'));
        foreach ($results as $index => &$result) {
            $result['peringkat'] = $index + 1;
            $result['alokasi_pupuk'] = $sumPreference > 0 
                ? ($result['total_preference'] / $sumPreference) * $totalPupuk 
                : 0;
        }

        return [
            'results' => $results,
            'normalized_weights' => $normalizedWeights,
            'kriterias' => $kriterias,
            'total_weight' => $totalWeight
        ];
    }

    private function getMetrics($petanis, $kriterias)
    {
        $metrics = [];
        foreach ($kriterias as $k) {
            $values = $petanis->map(fn($p) => $this->getPetaniValueForKriteria($p, $k))->toArray();
            $metrics[$k->id_kriteria] = [
                'min' => !empty($values) ? min($values) : 0,
                'max' => !empty($values) ? max($values) : 0,
            ];
        }
        return $metrics;
    }

    private function getPetaniValueForKriteria($petani, $kriteria)
    {
        // Simple mapping based on criteria name
        $name = strtolower($kriteria->nama_kriteria);
        if (str_contains($name, 'luas')) return $petani->luas_lahan;
        if (str_contains($name, 'produk')) return $petani->produktivitas_lahan;
        
        // For status kepemilikan, we might need a numerical mapping
        if (str_contains($name, 'milik') || str_contains($name, 'status')) {
            $status = strtolower($petani->status_kepemilikan_lahan);
            if ($status === 'milik sendiri') return 3;
            if ($status === 'sewa') return 2;
            return 1;
        }

        return 0;
    }
}
