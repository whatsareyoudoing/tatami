<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Kesalahan proses pesanan yang pesannya boleh ditampilkan ke user,
 * misalnya "Stok Light Blue tinggal 1".
 */
class OrderException extends RuntimeException
{
}
