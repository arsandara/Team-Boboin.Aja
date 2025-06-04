<?php

if (!function_exists('formatRupiah')) {
    /**
     * Format number to Indonesian Rupiah
     *
     * @param  int|float  $number
     * @return string
     */
    function formatRupiah($number)
    {
        return 'Rp. ' . number_format($number, 0, ',', '.');
    }
}