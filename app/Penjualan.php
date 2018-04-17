<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class Penjualan extends Model
{
    //
    use AuditableTrait;
    protected $fillable = (['no_faktur', 'total_bayar', 'status_pemesanan', 'pelanggan_id', 'cara_ayar', 'keterangan', 'toko_id', 'subtotal', 'tunai', 'kembalian', 'diskon', 'pajak',
    ]);

    public function kategoriProduk()
    {
        return $this->hasOne('App\KategoriProduk');
    }

    public function pelanggan()
    {
        return $this->hasOne('App\Pelanggan');
    }

    public function tbsPenjualan()
    {
        return $this->hasOne('App\TbsPenjualan');
    }

    public function scopeLaporanRingkas($query, $waktu)
    {
        $query->select(['subtotal', 'diskon'])->where('toko_id', Auth::user()->toko_id)
            ->where('created_at', '>=', $waktu);
        return $query;
    }

    public function scopeTotalPenjualan($query, $waktu)
    {
        $query->select([DB::raw('SUM(total_bayar) as total_pembayaran'), DB::raw('COUNT(*) as total_penjualan')])
            ->where('toko_id', Auth::user()->toko_id)
            ->where('created_at', '>=', $waktu);
        return $query;
    }

    public function scopeLaporanPerJam($query, $waktu)
    {
        $query->select([DB::raw('HOUR(created_at) as jam'), DB::raw('SUM(total_bayar) as total_pembayaran'), DB::raw('COUNT(*) as total_penjualan')])
            ->where('toko_id', Auth::user()->toko_id)
            ->where('created_at', '>=', $waktu)
            ->groupBy(DB::raw('HOUR(created_at)'));
        return $query;
    }

    public function scopeTransaksiPenjualan($query, $waktu)
    {
        $query->select([
            DB::raw('DATE(penjualans.created_at) as tanggal'),
            'penjualans.id as id_penjualan',
            'penjualans.subtotal',
            'penjualans.keterangan',
            'penjualans.no_faktur',
            'penjualans.diskon',
            'penjualans.pajak',
            'pelanggans.nama_pelanggan',
            'penjualans.cara_bayar',
        ])
            ->leftJoin('pelanggans', 'pelanggans.id', '=', 'penjualans.pelanggan_id')
            ->where('penjualans.toko_id', Auth::user()->toko_id)
            ->where('.penjualans.created_at', '>=', $waktu)
            ->groupBy('penjualans.id');
        return $query;
    }

    public function scopeLaporanPenjualanHarian($query, $waktu)
    {
        $query->select([
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('SUM(total_bayar) as total_pembayaran'),
            DB::raw('COUNT(*) as total_penjualan'),
        ])
            ->where('toko_id', Auth::user()->toko_id)
            ->where('created_at', '>=', $waktu)->groupBy(DB::raw('DATE(created_at)'));
        return $query;
    }

    public function scopePerilakuPelanggan($query, $id)
    {
        $query->select(DB::raw('SUM(total_bayar) as total_bayars'))
            ->where('pelanggan_id', $id);
        return $query;
    }

    public function scopeTerakhirDatang($query, $id)
    {
        $query->select(DB::raw('created_at as created_at'))
            ->where('pelanggan_id', $id)
            ->orderBy('created_at', 'desc');
        return $query;
    }

    public function scopeRataRataDatang($query, $id, $waktu)
    {
        $query->select(DB::raw('DATE(created_at) as created_at'))
            ->where('pelanggan_id', $id)
            ->where('created_at', '>=', $waktu)
            ->groupBy(DB::raw('DATE(created_at)'));
    }

    public function scopeRiwayatTransaksiPelanggan($query, $request)
    {
        $query->select([
            'penjualans.id as id_penjualan',
            'penjualans.total_bayar',
            'penjualans.created_at  AS waktu',
        ])
            ->where('penjualans.toko_id', Auth::user()->toko_id)
            ->where('penjualans.pelanggan_id', $request->id)
            ->groupBy('penjualans.id');
        return $query;
    }
    public function scopeDiskonTransaksiPelanggan($query, $request)
    {
        $query->select(DB::raw('SUM(diskon) as diskon'),
            DB::raw('SUM(total_bayar) as total_bayar'),
            'penjualans.pelanggan_id')
            ->where('penjualans.toko_id', Auth::user()->toko_id)
            ->where('penjualans.pelanggan_id', $request->id)
            ->groupBy('penjualans.pelanggan_id');
        return $query;
    }

    public function scopeCetakPenjualan($query, $id_penjualan)
    {
        $query->select([
            'penjualans.created_at as waktu',
            'penjualans.id',
            'penjualans.total_bayar',
            'penjualans.subtotal',
            'penjualans.diskon',
            'penjualans.tunai',
            'penjualans.kembalian',
            'penjualans.pelanggan_id',
            'pelanggans.nama_pelanggan',
            'users.nama_pemilik',
        ])
            ->leftJoin('pelanggans', 'pelanggans.id', '=', 'penjualans.pelanggan_id')
            ->leftJoin('users', 'users.id', '=', 'penjualans.created_by')
            ->where('penjualans.id', $id_penjualan)
            ->where('penjualans.toko_id', Auth::user()->toko_id);
        return $query;
    }

    public function queryRiwayatPenjualan()
    {
        $penjualan = Penjualan::select([
            'penjualans.created_at as waktu',
            'penjualans.id',
            'penjualans.total_bayar',
            'penjualans.subtotal',
            'penjualans.diskon',
            'penjualans.tunai',
            'penjualans.kembalian',
            'penjualans.pelanggan_id',
            'pelanggans.nama_pelanggan',
            'users.nama_pemilik',
        ])
            ->leftJoin('pelanggans', 'pelanggans.id', '=', 'penjualans.pelanggan_id')
            ->leftJoin('users', 'users.id', '=', 'penjualans.created_by')
            ->where('penjualans.toko_id', Auth::user()->toko_id);
        return $penjualan;
    }

    public function createDate($waktu)
    {
        $date   = date_create($waktu);
        $format = date_format($date, 'Y-m-d');
        return $format;
    }

    // menampilakan riwayat penjualan
    public function scopeRiwayatPenjualan($query, $request, $hari, $minggu, $bulan, $tahun)
    {
        if ($request->dari_tanggal != "" && $request->sampai_tanggal != "") {
            $query = $this->queryRiwayatPenjualan()
                ->where(DB::raw('DATE(penjualans.created_at)'), '>=', $this->createDate($request->dari_tanggal))
                ->where(DB::raw('DATE(penjualans.created_at)'), '<=', $this->createDate($request->sampai_tanggal))
                ->orderBy('penjualans.id', 'desc');
            # code...
        } elseif ($request->dari_tanggal == "" && $request->sampai_tanggal == "") {
            if ($request->priode == 0 || $request->priode == "") {
                $query = $this->queryRiwayatPenjualan()
                    ->orderBy('penjualans.id', 'desc');
                # code...
            }
            if ($request->priode == 1) {
                $query = $this->queryRiwayatPenjualan()
                    ->where('penjualans.created_at', '>=', $hari)
                    ->orderBy('penjualans.id', 'desc');
                # code...
            }if ($request->priode == 2) {
                $query = $this->queryRiwayatPenjualan()
                    ->where('penjualans.created_at', '>=', $minggu)
                    ->orderBy('penjualans.id', 'desc');
                # code...
            }if ($request->priode == 3) {
                $query = $this->queryRiwayatPenjualan()
                    ->where('penjualans.created_at', '>=', $bulan)
                    ->orderBy('penjualans.id', 'desc');
                # code...
            }if ($request->priode == 4) {
                $query = $this->queryRiwayatPenjualan()
                    ->where('penjualans.created_at', '>=', $tahun)
                    ->orderBy('penjualans.id', 'desc');
                # code...
                # code...
            }
        }
        return $query;
    }
}
