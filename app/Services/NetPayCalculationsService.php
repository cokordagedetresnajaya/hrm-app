<?php

namespace App\Services;

class NetPayCalculationsService
{
    private $grossSalary;
    private $maritalStatus;
    private $dependentsCount;
    /**
     * Create a new class instance.
     */
    public function __construct(float $grossSalary, string $maritalStatus = 'Single', int $dependentsCount = 0)
    {
        $this->grossSalary = $grossSalary;
        $this->maritalStatus = $maritalStatus;
        $this->dependentsCount = $dependentsCount;
    }

    public function calculateBPJSKesehatan(): float
    {
        return $this->grossSalary * 0.01; // 1%
    }

    public function calculateBPJSJP(): float
    {
        $gajiDasar = min($this->grossSalary, 9559600);
        return $gajiDasar * 0.01; // 1%
    }

    public function calculateBPJSJHT(): float
    {
        return $this->grossSalary * 0.02; // 2%
    }

    public function calculateBiayaJabatan(): float
    {
        $biayaJabatan = $this->grossSalary * 0.05;
        return min($biayaJabatan, 500000); // max Rp.500.000/month
    }

    public function getPTKP(): float
    {
        $basePTKP = 54000000; // Default TK/0 (Tidak Kawin, 0 tanggungan)

        // Jika Married (K/0), tambahkan Rp. 4.500.000
        if (strtolower($this->maritalStatus) === 'married') {
            $basePTKP += 4500000;
        }

        // Tambahkan PTKP untuk jumlah tanggungan (maksimal 3)
        $tanggungan = min($this->dependentsCount, 3);
        $basePTKP += $tanggungan * 4500000;

        return $basePTKP;
    }

    public function calculatePKP(): float
    {
        $bpjsDeductions = $this->calculateBPJSKesehatan() + $this->calculateBPJSJHT() + $this->calculateBPJSJP();
        $biayaJabatan = $this->calculateBiayaJabatan();
        $nettoIncomePerMonth = $this->grossSalary - $bpjsDeductions - $biayaJabatan - $this->calculateBPJSJP();
        $yearlyNettoIncome = $nettoIncomePerMonth * 12;
        $pkp = $yearlyNettoIncome - $this->getPTKP();
        return max(0, floor($pkp / 1000) * 1000); // dibulatkan ke ribuan bawah
    }

    public function calculatePPh21(): float
    {
        $pkp = $this->calculatePKP();
        $pph = 0;

        $tiers = [
            [60000000, 0.05],    // 0 – 60 juta
            [190000000, 0.15],   // 60 – 250 juta (250 - 60)
            [250000000, 0.25],   // 250 – 500 juta
            [4500000000, 0.30],  // 500 juts - 5 miliar (4.5 M = 5M - 500jt)
            [PHP_INT_MAX, 0.35],  // 35% sisanya
        ];

        foreach ($tiers as $tier) {
            if ($pkp <= 0) break;

            $limit = $tier[0];
            $rate = $tier[1];

            if ($pkp > $limit) {
                $pph += $limit * $rate;
                $pkp -= $limit;
            } else {
                $pph += $pkp * $rate;
                $pkp = 0;
            }
        }

        return $pph / 12; // dibagi 12 bulan
    }

    public function getDeductions()
    {
        $potonganBPJS = $this->calculateBPJSKesehatan() + $this->calculateBPJSJHT() + $this->calculateBPJSJP();
        $potonganPPh21 = $this->calculatePPh21();
        return $potonganBPJS + $potonganPPh21;
    }

    public function calculateNetPay(): float
    {
        return $this->grossSalary - $this->getDeductions();
    }
}
