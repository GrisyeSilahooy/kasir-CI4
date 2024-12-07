<?php

namespace App\Models;

use CodeIgniter\Model;

class Mpenjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'PenjualanID';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['PenjualanID', 'TanggalPenjualan', 'TotalHarga', 'PelangganID'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function totalPendapatanHariIni()
    {
        $penjualan = new Mpenjualan;
        $penjualan->select('sum(TotalHarga) as TotalPendapatan');
        $penjualan->groupby('TanggalPenjualan');
        $penjualan->where('Year(TanggalPenjualan)', date('Y'));
        $penjualan->where('Month(TanggalPenjualan)', date('m'));
        $penjualan->where('Day(TanggalPenjualan)', date('d'));
        return $penjualan->findAll();
    }

    public function totalPendapatanBulaniIni()
    {
        $penjualan = new Mpenjualan;
        $penjualan->select('sum(TotalHarga) as TotalPendapatan');
        $penjualan->groupby('TanggalPenjualan');
        $penjualan->where('Year(TanggalPenjualan)', date('Y'));
        $penjualan->where('Month(TanggalPenjualan)', date('m'));
        return $penjualan->findAll();
    }

    public function pendapatanBlnSebelumnya()
    {
        $penjualan = new Mpenjualan;
        $penjualan->select("Concat(Year(TanggalPenjualan),'-',MONTH(TanggalPenjualan)) as Periode,sum(TotalHarga) as TotalPendapatan");
        $penjualan->groupby("Concat(Year(TanggalPenjualan),'-',MONTH(TanggalPenjualan))");
        $penjualan->orderby("Concat(Year(TanggalPenjualan),'-',MONTH(TanggalPenjualan))", "desc", false);
        $penjualan->limit(2);
        return $penjualan->find();
    }

    public function sourceGrafikTrendPenualan($tahun)
    {
        $penjualan = new Mpenjualan;
        $penjualan->select("MONTH(TanggalPenjualan) as Periode , CONCAT(MONTH(TanggalPenjualan),'/',YEAR(TanggalPenjualan)) As PeriodeTahun, SUM(TotalHarga) as TotalPendapatan");
        $penjualan->groupby("MONTH(TanggalPenjualan)");
        $penjualan->groupby("CONCAT(MONTH(TanggalPenjualan),'/',YEAR(TanggalPenjualan))");
        $penjualan->orderby('MONTH(TanggalPenjualan)', 'asc');
        $penjualan->where("YEAR(TanggalPenjualan)", $tahun);
        return $penjualan->find();
    }

    public function laporanPendapatan($jenis, $periode)
    {
        if ($jenis == 'harian') {
            $penjualan = new Mpenjualan;
            $penjualan->select('penjualan.TanggalPenjualan,
            (detailpenjualan.JumlahProduk * produk.Harga) AS HargaJual,
            (detailpenjualan.JumlahProduk * produk.HargaBeli) AS HargaBeli,
            (detailpenjualan.JumlahProduk * produk.Harga)-(detailpenjualan.JumlahProduk * produk.HargaBeli) AS Margin');
            $penjualan->join('detailpenjualan', 'penjualan.PenjualanID=detailpenjualan.PenjualanID');
            $penjualan->join('produk', 'produk.ProdukID=detailpenjualan.ProdukID');
            $penjualan->where('penjualan.TanggalPenjualan', $periode);
        } else if ($jenis == 'bulanan') {
            $periodeTemp = explode('-', $periode);
            $periodeBln = $periodeTemp[0] . '-' . $periodeTemp[1];
            $penjualan = new Mpenjualan;
            $penjualan->select('penjualan.TanggalPenjualan,
            sum(detailpenjualan.JumlahProduk * produk.Harga) AS HargaJual,
            sum(detailpenjualan.JumlahProduk * produk.HargaBeli) AS HargaBeli,
            sum((detailpenjualan.JumlahProduk * produk.Harga)-(detailpenjualan.JumlahProduk * produk.HargaBeli)) AS Margin');
            $penjualan->join('detailpenjualan', 'penjualan.PenjualanID=detailpenjualan.PenjualanID');
            $penjualan->join('produk', 'produk.ProdukID=detailpenjualan.ProdukID');
            $penjualan->groupby('TanggalPenjualan');
            $penjualan->groupby('(detailpenjualan.JumlahProduk * produk.Harga)');
            $penjualan->groupby('(detailpenjualan.JumlahProduk * produk.HargaBeli)');
            $penjualan->groupby('(detailpenjualan.JumlahProduk * produk.Harga)-(detailpenjualan.JumlahProduk * produk.HargaBeli)');
            $penjualan->like('TanggalPenjualan', $periodeBln);
        }

        return $penjualan->findAll();
    }

    // Edit Penjualan
    public function editPenjualan($penjualanID, $data)
    {
        return $this->update($penjualanID, $data);
    }

    // Edit Detail Penjualan
    public function editDetailPenjualan($penjualanID, $data)
    {
        // Assuming `detailpenjualan` is a related table.
        $this->db->table('detailpenjualan')
            ->where('PenjualanID', $penjualanID)
            ->update($data);
    }

    // Delete Penjualan and associated Detail Penjualan
    public function deletePenjualan($penjualanID)
    {
        // First, delete the detailpenjualan records associated with the penjualan
        $this->db->table('detailpenjualan')->where('PenjualanID', $penjualanID)->delete();

        // Now, delete the penjualan record
        return $this->delete($penjualanID);
    }
}
