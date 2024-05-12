<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Contacts;
use Livewire\WithFileUploads;
use App\Export\ContactExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Import\ContactImport;
class Contact extends Component
{
    public $contacts, $name, $email, $contact_id,$mobile_no,$file;
    public $isOpen,$isImport,$isExport = 0;
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    use WithFileUploads;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function render()
    {
        $this->contacts = Contacts::orderBy($this->sortBy, $this->sortDirection)->get();
        return view('livewire.contact');
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $field;
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
    $download = Excel::download(new ContactExport($contact), 'contact.xlsx');
    return $download;
   }


   public function closeImport(){
    $this->isImport = false;
   }
   public function fileImport(){
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
        ]);

        Contacts::updateOrCreate(['id' => $this->contact_id], [
            'name' => $this->name,
            'email' => $this->email,
            'mobile_no'=>$this->mobile_no
        ]);

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
