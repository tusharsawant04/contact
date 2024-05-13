<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Contacts;
use Livewire\WithFileUploads;
use App\Export\ContactExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Import\ContactImport;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Contact extends Component
{
    public  $name, $email, $contact_id,$mobile_no,$file;
    public $isOpen,$isImport,$isExport = 0;
    public $sort = 'name';
    public $sortDirection = 'asc';
    public $image;
    public $croppedImage;
    public $search;
    public $profilePhoto;
    use WithFileUploads;
    use WithPagination;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function render()
    {

        $query = Contacts::query();
        if ($this->search) {
            $query=new Contacts();
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('mobile_no', 'like', '%' . $this->search . '%');
        }

        $query->orderBy($this->sort, $this->sortDirection);
        $contacts = $query->paginate(3);
        return view('livewire.contact', [
            'contacts' => $contacts
        ]);
    }

    public function triggerSearch()
    {
        $this->render(); // Manually trigger Livewire render method
    }
    public function sortBy($field)
    {
        if ($this->sort === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sort = $field;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }
   public function import(){
        $this->isImport=true;
   }
   public function export(){
    $contact = Contacts::all();
    $this->isImport = false;
    $download = Excel::download(new ContactExport(), 'contact.xlsx');
    return $download;
   }


   public function closeImport(){
    $this->isImport = false;
   }
   public function fileImport(){
    $this->validate([
        'file' => 'required|file|mimes:xlsx,xls|max:10240',
    ]);
    Excel::import(new ContactImport, $this->file);
}

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function openModal()
    {
        $this->isOpen = true;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function closeModal()
    {
        $this->isOpen = false;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields(){
        $this->name = '';
        $this->email = '';
        $this->contact_id = '';
        $this->mobile_no = '';
        $this->profilePhoto='';

    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email', // Adding email validation rule
            'mobile_no' => 'required|regex:/^\d{10}$/',
            'profilePhoto' => 'required|image|max:1024',
        ]);


       $Contacts= Contacts::updateOrCreate(['id' => $this->contact_id], [
            'name' => $this->name,
            'email' => $this->email,
            'mobile_no'=>$this->mobile_no,
        ]);
        if ($this->profilePhoto) {
            $path = $this->profilePhoto->storePublicly('profile_photos', 'public');
            $Contacts->profile_photo = Storage::disk('public')->url($path);     }
        $Contacts->save();
        session()->flash('message',
            $this->contact_id ? 'contact Updated Successfully.' : 'contact Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function edit($id)
    {
        $contact = Contacts::findOrFail($id);
        $this->contact_id = $id;
        $this->name = $contact->name;
        $this->email = $contact->email;
        $this->mobile_no=$contact->mobile_no;

        $this->openModal();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function delete($id)
    {
        contacts::find($id)->delete();
        session()->flash('message', 'contact Deleted Successfully.');
    }
}
