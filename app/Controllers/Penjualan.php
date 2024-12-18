<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Penjualan extends BaseController
{
    public function index()
    {
        $data = [
            'judulHalaman' => '<i class="mdi mdi-apple-keyboard-caps"></i> Penjualan Produk',
            'listPelanggan' => $this->pelanggan->findAll(),
            'listProduk' => $this->produk->findAll(),
            'listProdukTerjual' => $this->detailpenjualan->listBarangTerjual(['PenjualanID' => session()->get('IdPenjualan')]),
        ];

        $validasiForm = [
            'opsiPelanggan' => 'required',
            'opsiProduk' => 'required',
            'txtJumlahJual' => 'required',
        ];

        if ($this->validate($validasiForm)) {
            if (session()->get('IdPenjualan') == null) {
                $cekStokBarang = $this->produk->where(['ProdukID' => $this->request->getPost('opsiProduk')])->findAll();

                if ($cekStokBarang[0]['Stok'] > $this->request->getPost('txtJumlahJual')) {
                    $dataPenjualan = [
                        'TanggalPenjualan' => date('Y-m-d'),
                        'PelangganID' => $this->request->getPost('opsiPelanggan'),
                        'TotalHarga' => 0,
                    ];

                    $this->penjualan->insert($dataPenjualan);

                    // get harga jual
                    $cekHarga = $this->produk->where(['ProdukID' => $this->request->getPost('opsiProduk')])->findAll();
                    $hargaJual = $this->request->getPost('txtJumlahJual') * $cekHarga[0]['Harga'];

                    // save to detail
                    $dataDetailPenjualan = [
                        'PenjualanID' => $this->penjualan->getInsertID(),
                        'ProdukID' => $this->request->getPost('opsiProduk'),
                        'JumlahProduk' => $this->request->getPost('txtJumlahJual'),
                        'SubTotal' => $hargaJual,
                    ];

                    $this->detailpenjualan->insert($dataDetailPenjualan);

                    $sessionData = [
                        'IdPenjualan' => $this->penjualan->getInsertID(),
                        'IdPelanggan' => $this->request->getPost('opsiPelanggan'),
                    ];

                    session()->set($sessionData);
                    return redirect()->to(site_url('/penjualan'))->with('pesan', '<div class="alert alert-warning">Produk berhasil disimpan</div>');
                } else {
                    return redirect()->to(site_url('/penjualan'))->with('pesan', '<div class="alert alert-warning">Jumlah barang dibeli melebihi stok</div>');
                }
            } else {
                // untuk orang yang sama di barang ke-2
                $cekHarga = $this->produk->where(['ProdukID' => $this->request->getPost('opsiProduk')])->findAll();
                $hargaJual = $this->request->getPost('txtJumlahJual') * $cekHarga[0]['Harga'];

                // save to detail untuk produk ke-2
                $dataDetailPenjualan = [
                    'PenjualanID' => session()->get('IdPenjualan'),
                    'ProdukID' => $this->request->getPost('opsiProduk'),
                    'JumlahProduk' => $this->request->getPost('txtJumlahJual'),
                    'SubTotal' => $hargaJual,
                ];

                $this->detailpenjualan->insert($dataDetailPenjualan);
                return redirect()->to(site_url('/penjualan'))->with('pesan', '<div class="alert alert-warning">Produk berhasil disimpan</div>');
            }
        }

        return view('Penjualan/form-penjualan', $data);
    }

    public function formBayar()
    {
        $data = [
            'judulHalaman' => '<i class="mdi mdi-apple-keyboard-caps"></i> Pembayaran',
            'detailPenjualan' => $this->penjualan->where(['PenjualanID' => session()->get('IdPenjualan')])->find(),
        ];

        return view('Penjualan/form-bayar', $data);
    }

    public function bayar()
    {
        session()->remove('IdPenjualan');
        session()->remove('IdPelanggan');
        return redirect()->to(site_url('/penjualan'));
    }

    // Edit Function for Penjualan
    public function edit($penjualanID)
    {
        $penjualan = $this->penjualan->find($penjualanID);
        if (!$penjualan) {
            return redirect()->to(site_url('/penjualan'))->with('pesan', '<div class="alert alert-danger">Penjualan tidak ditemukan</div>');
        }

        $data = [
            'judulHalaman' => '<i class="mdi mdi-apple-keyboard-caps"></i> Edit Penjualan Produk',
            'penjualan' => $penjualan,
            'listPelanggan' => $this->pelanggan->findAll(),
            'listProduk' => $this->produk->findAll(),
            'detailPenjualan' => $this->detailpenjualan->where(['PenjualanID' => $penjualanID])->findAll(),
        ];

        return view('Penjualan/form-edit-penjualan', $data);
    }

    public function update($penjualanID)
    {
        $validasiForm = [
            'opsiPelanggan' => 'required',
            'opsiProduk' => 'required',
            'txtJumlahJual' => 'required',
        ];

        if ($this->validate($validasiForm)) {
            $cekHarga = $this->produk->where(['ProdukID' => $this->request->getPost('opsiProduk')])->findAll();
            $hargaJual = $this->request->getPost('txtJumlahJual') * $cekHarga[0]['Harga'];

            $dataDetailPenjualan = [
                'PenjualanID' => $penjualanID,
                'ProdukID' => $this->request->getPost('opsiProduk'),
                'JumlahProduk' => $this->request->getPost('txtJumlahJual'),
                'SubTotal' => $hargaJual,
            ];

            // Update detail penjualan
            $this->detailpenjualan->update($this->request->getPost('detailPenjualanID'), $dataDetailPenjualan);
            return redirect()->to(site_url('/penjualan'))->with('pesan', '<div class="alert alert-warning">Produk berhasil diperbarui</div>');
        }

        return redirect()->to(site_url('/penjualan'))->with('pesan', '<div class="alert alert-danger">Form tidak valid</div>');
    }

    // Delete Function for DetailPenjualan
    public function delete($detailPenjualanID)
    {
        $this->detailpenjualan->delete($detailPenjualanID);
        return redirect()->to(site_url('/penjualan'))->with('pesan', '<div class="alert alert-warning">Produk berhasil dihapus</div>');
    }
}
