<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Diagnosis;
use App\DiagnosisImage;
use App\DiagnosisLivestock;
use App\Livestock;
use File;
use Redirect;

class DiagnosisController extends Controller
{
    public function indexForFarmer()
    {
        $count_complaint = Diagnosis::all()->count(); // Jumlah Keluhan
        $count_diagnosis = Diagnosis::where('diagnosis', '!=', null)->get()->count(); // Jumlah keluhan yang di jawab
        $count_livestock_infected = Livestock::where('status', '!=', 'mati')
        ->where('status', '!=', 'Sudah dibeli')
        ->whereHas('disease', function ($query) 
        {
            $query->where('status', 'terjangkit');
        })
        ->get()->count(); // Jumlah hewan yang terjangkit 
        
        $complaints =  Diagnosis::where('diagnosis', null)->get();
        $diagnosis =  Diagnosis::where('diagnosis', '!=', null)->get();

        return view('manajemen.diagnosis.index_farmer', compact(
            'count_complaint',
            'count_diagnosis',
            'count_livestock_infected',
            'complaints',
            'diagnosis'
        ));
        
    }

    public function indexForDoctor()
    {
        $count_complaint = Diagnosis::all()->count(); // Jumlah Keluhan
        $count_diagnosis = Diagnosis::where('diagnosis', '!=', null)->get()->count(); // Jumlah keluhan yang di jawab
        $count_diagnosis_waiting = Diagnosis::where('diagnosis', null)->get()->count(); // Jumlah hewan yang belum dijawab
        
        $complaints =  Diagnosis::where('diagnosis', null)->get();
        $diagnosis =  Diagnosis::where('diagnosis', '!=', null)->get();

        return view('manajemen.diagnosis.index_doctor', compact(
            'count_complaint',
            'count_diagnosis',
            'count_diagnosis_waiting',
            'complaints',
            'diagnosis'
        ));
        
    }

    public function index()
    {
        // $jumlah_hewan = Livestock::count();
        // $livestock = Livestock::paginate($batas);

        // $livestock_sudah_mati = Livestock::where('status','Mati')->get();
        // $livestock_sudah_dibeli = Livestock::where('status','Sudah dibeli')->get();
        // $livestock_belum_dibeli = Livestock::where('status','Belum dibeli')->get();

        $diagnosis = Diagnosis::all();
        
        $jumlah_livestock = Livestock::count();
        $jumlah_dibeli = $livestock_sudah_dibeli->count();
        $jumlah_mati = $livestock_sudah_mati->count();    

        return view('manajemen.livestock', compact('livestock',
        'no',
        'jumlah_hewan',
        'jumlah_stock',
        'jumlah_dibeli',
        'jumlah_mati'));
    }

    public function createComplaint()
    {
        return view('manajemen.diagnosis.create_complaint');
    }

    public function storeComplaint(Request $request)
    {
        $this->validate($request,[
            'complaint' => 'required',
            'image' => 'required',
            'image.*' => 'image|mimes:png,jpeg,jpg|max:1024'
        ],
        [
            'complaint.required' => 'Gejala penyakit hewan tidak boleh kosong',
            'image.required' => 'Foto gejala tidak boleh kosong',
            'image.*.image' => 'Foto gejala harus berupa gambar.',
            'image.*.mimes' => 'Format Foto gejala hanya berupa PNG, JPG dan JPEG',
            'image.*.max' => 'Ukuran foto maksimal 1 mb'
        ]);

        $complaint = Diagnosis::create([
            'complaint' => $request->complaint,
            'code' => 'CS'."-".time(),
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $numberPath = 1; 
            $index = 0;
            foreach ($file as $files) {
            
                 $filename = $complaint->code .'-'.$numberPath.'.'.$files->getClientOriginalExtension();
                 
                 $files->move('Diagnosis', $filename);
                 
                 $data[$index]['filename'] = $filename;
                 $data[$index]['relation_id'] = $complaint->id;
    
                 $numberPath++;
                 $index++;
            }
        }

        foreach ($data as $image){
            $diseaseImage = DiagnosisImage::create([
                'image' => $image['filename'],
                'diagnosis_id' => $image['relation_id'],
            ]);
        }

        return redirect(route('diagnosis.detail', $complaint->code))->with('pesan','Data Konsultasi Penyakit Ternak Anda Berhasil Disimpan');
    }

    public function makeDiagnosis($code)
    {
        $complaint = Diagnosis::with(['images'])->where('code', $code)->first(); 
        return view('manajemen.diagnosis.make_diagnosis', compact('complaint'));
    }

    public function storeDiagnosis(Request $request, $code)
    {
        $this->validate($request,[
            'diagnosis' => 'required',
            'treatment' => 'required',
        ],
        [
            'diagnosis.required' => 'Diagnosis tidak boleh kosong',
            'treatment.required' => 'Perawatan atau pengobatan penyakit hewan tidak boleh kosong',
        ]);

        $diagnosis = Diagnosis::where('code', $code)->first();

        $diagnosis->update([
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
        ]);

        return redirect(route('diagnosis.detail', $diagnosis->code))->with('pesan','Diagnosis dan Treatment Anda Berhasil Disimpan');
    }

    public function detailDiagnosis($code)
    {
        $diagnosis = Diagnosis::with(['images'])->where('code', $code)->first(); 
        $livestocks = DiagnosisLivestock::with(['livestock'])->where('diagnosis_id', $diagnosis->id)->get();
        
        $diagnosis_id = $diagnosis->id;
        $getLivestocks = Livestock::select(['id','kode','kode_kandang','jenis'])->where('status', '!=', 'mati')
        ->where('status', '!=', 'Sudah dibeli')
        ->whereDoesntHave('disease', function ($query) use ($diagnosis_id) 
        {
            $query->where('diagnosis_id', $diagnosis_id);
        })
        ->get(); 
        // dd($livestocks);
        return view('manajemen.diagnosis.detail_diagnosis', compact('diagnosis','livestocks','getLivestocks'));
    }

    public function changeStatusLivestock($livestockDiagnosisId, $status)
    {
        $livestock = DiagnosisLivestock::find($livestockDiagnosisId);
        
        $livestock->update([
            'status' => $status,
        ]);

        return Redirect::back()->withInput()->with('pesan','Status Hewan Kode : '.$livestock->livestock->kode.', Berhasil Disimpan');

    }

    public function addDiagnosisLivestock(Request $request, $livestockDiagnosisId)
    {
        $livestockInfected = DiagnosisLivestock::create([
            'livestock_id' => $request->livestock,
            'diagnosis_id' => $livestockDiagnosisId,
            'status' => 'terjangkit',
        ]);

        return Redirect::back()->withInput()->with('pesan','Data Tambahan Hewan Terinfeksi Berhasil Disimpan');
    }

    public function deletedDiagnosisLivestock($id)
    {
        $livestockDiagnosis = DiagnosisLivestock::findOrFail($id);

        $livestockDiagnosis->delete();

        return Redirect::back()->withInput()->with('pesan','Data Rekaman Pemeriksaan Hewan Berhasil Dihapus');
    }



    public function editDiagnosis()
    {

    }

    public function updateDiagnosis()
    {

    }

    public function deletedDiagnosis()
    {

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manajemen.LivestockManajemen.create_livestock');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'jenis' => 'required',
            'jenis_kelamin' => 'required',
            'warna' => 'required|string',
            'tgl_lahir' => 'required|date',
            'foto'=>'image|mimes:jpeg,jpg,png|max:1024',
        ],
        [
            'jenis.required' => 'Jenis hewan tidak boleh kosong',
            'jenis_kelamin.required' => 'Jenis kelamin hewan tidak boleh kosong',
            'warna.required' => 'Warna hewan tidak boleh kosong',
            'tgl_lahir.required' => 'Tanggal lahir hewan tidak boleh kosong',
            'tgl_lahir.date' => 'Tanggal lahir hewan harus berbentuk tanggal, dengan format tanggal/bulan/tahun',
            'foto.*.image' => 'Foto hewan harus berupa gambar.',
            'foto.*.mimes' => 'Format Foto hewan hanya berupa PNG, JPG dan JPEG',
            'foto.*.max' => 'Ukuran foto maksimal 1 mb'
        ]);


        $livestock = new Livestock;
        $livestock->jenis = $request->jenis;
        $livestock->type = $request->type;
        $livestock->kode_kandang = $request->kode_kandang;
        $livestock->jenis_kelamin = $request->jenis_kelamin;
        $livestock->warna = $request->warna;
        $livestock->tgl_lahir = $request->tgl_lahir;
        $livestock->description = $request->description;
        $livestock->berat = (int)preg_replace("/[^0-9]/", "", $request->berat);
        $livestock->harga = (int)preg_replace("/[^0-9]/", "", $request->harga);

        
        $livestock->status = "Belum dibeli";

        if (request('foto')!= null){

            $foto = $request->foto;
            $namafile = time().'.'.$foto->getClientOriginalExtension();

            $livestock->foto = $namafile;
            $foto->move('images/livestock/', $namafile);
        }


        $livestock->save();

        if($livestock->jenis == "cow" ){
            $livestock->kode = "SP-".$livestock->id;
        }

        if($livestock->jenis == "goat" ){
            $livestock->kode = "GT-".$livestock->id;
        }

        if($livestock->jenis == "sheep" ){
            $livestock->kode = "SH-".$livestock->id;
        }
        
        $livestock->save();

        return redirect('/manager/livestock')->with('pesan','Data Hewan Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $livestock = Livestock::find($id);
        
        return view('manajemen.LivestockManajemen.detail_livestock', compact('livestock'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $livestock = Livestock::find($id);
        
        return view('manajemen.LivestockManajemen.update_livestock', compact('livestock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'jenis' => 'required',
            'jenis_kelamin' => 'required',
            'warna' => 'required|string',
            'tgl_lahir' => 'required|date',
            'foto'=>'image|mimes:jpeg,jpg,png',
        ],[
            'jenis.required' => 'Jenis hewan tidak boleh kosong',
            'jenis_kelamin.required' => 'Jenis kelamin hewan tidak boleh kosong',
            'warna.required' => 'Warna hewan tidak boleh kosong',
            'tgl_lahir.required' => 'Tanggal lahir hewan tidak boleh kosong',
            'tgl_lahir.date' => 'Tanggal lahir hewan harus berbentuk tanggal, dengan format tanggal/bulan/tahun',
            'foto.*.image' => 'Foto hewan harus berupa gambar.',
            'foto.*.mimes' => 'Format Foto hewan hanya berupa PNG, JPG dan JPEG',
            'foto.*.max' => 'Ukuran foto maksimal 1 mb'
        ]);


        $livestock = Livestock::find($id);
        $livestock->jenis = $request->jenis;
        $livestock->type = $request->type;
        $livestock->kode_kandang = $request->kode_kandang;
        $livestock->jenis_kelamin = $request->jenis_kelamin;
        $livestock->warna = $request->warna;
        $livestock->tgl_lahir = $request->tgl_lahir;
        $livestock->description = $request->description;
        $livestock->berat = (int)preg_replace("/[^0-9]/", "", $request->berat);
        $livestock->harga = (int)preg_replace("/[^0-9]/", "", $request->harga);

        if (request('foto')!= null){

            File::delete('images/livestock/'.$livestock->foto);
            
            $foto = request('foto');
            $namafile = time().'.'.$foto->getClientOriginalExtension();
            $foto->move('images/livestock/', $namafile);
            $livestock->foto = $namafile;
            
        }

        $livestock->save();
        
        return redirect('/manager/livestock')->with('pesan','Data Hewan Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $livestock = Livestock::find($id);
        $livestock -> delete();

        $namafile = $livestock->foto;
        File::delete('images/livestock/'.$namafile);


        return redirect('/manager/livestock')->with('pesan','Data Hewan Berhasil Dihapus');
    }

    public function mati(Request $request, $id)
    {
        $livestock = Livestock::find($id);
        $livestock->status = "Mati";
        $livestock->save();

        return redirect('/manager/livestock')->with('pesan','Data Hewan Telah Diganti Dari Hidup Menjadi Sudah Mati');
    }

    public function sudahBeli(Request $request, $id)
    {
        $livestock = Livestock::find($id);
        $livestock->status = "Sudah dibeli";
        $livestock->save();

        return redirect('/manager/livestock')->with('pesan','Data Hewan Telah Diganti Dari Hidup Menjadi Sudah Dibeli');
    }

    public function belumBeli(Request $request, $id)
    {
        $livestock = Livestock::find($id);
        $livestock->status = "Belum dibeli";
        $livestock->save();

        return redirect('/manager/livestock')->with('pesan','Data Hewan Telah Diganti Dari Hidup Menjadi Belum Dibeli');
    }

    public function search(Request $request)
    {
        $batas = 5;
        $cari = $request->kata;

        $jumlah_hewan = Livestock::count();
        $livestock = Livestock::where('kode','like',"%".$cari."%")
        ->orwhere('kode_kandang','like',"%".$cari."%")
        ->orwhere('jenis','like',"%".$cari."%")
        ->orwhere('type','like',"%".$cari."%")
        ->orwhere('warna','like',"%".$cari."%")
        ->orwhere('tgl_lahir','like',"%".$cari."%")
        ->orwhere('berat','like',"%".$cari."%")
        ->paginate($batas);;
        
        $no = $batas *($livestock->currentPage()-1);
        
        $livestock_sudah_mati = Livestock::where('status','Mati')->get();
        $livestock_sudah_dibeli = Livestock::where('status','Sudah dibeli')->get();
        $livestock_belum_dibeli = Livestock::where('status','Belum dibeli')->get();

        
        $jumlah_stock = $livestock_belum_dibeli->count();
        $jumlah_dibeli = $livestock_sudah_dibeli->count();
        $jumlah_mati = $livestock_sudah_mati->count();    

        return view('manajemen.LivestockManajemen.search_livestock', compact('livestock',
        'no',
        'jumlah_hewan',
        'jumlah_stock',
        'jumlah_dibeli',
        'jumlah_mati'));
    }
}
