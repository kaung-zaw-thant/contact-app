<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacts.index', ['contacts' => Contact::LatestFirst()->paginate(10),
        'companies' => Company::orderBy('name')->pluck('name','id')->prepend('All Companies', '')]);
    }

    public function create()
    {
        return view('contacts.create',['companies'=>Company::orderBy('name')->pluck('name','id')->prepend('All Companies', ''),
                                        'contact'=>new Contact]);
    }

    public function store()
    {
        $request = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required | email',
            'phone' => 'required',
            'address' => 'required',
            'company_id' => 'required | exists:companies,id'
        ]);
        Contact::create($request);

        return redirect()->route('contacts.index')->with('message', "Contact has been added successfully");
    }

    public function edit($id)
    {
        return view('contacts.edit',['companies'=>Company::orderBy('name')->pluck('name','id')->prepend('All Companies', ''),
                                    'contact'=> Contact::findOrFail($id)]);
    }

    public function update($id)
    {
        $request = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required | email',
            'phone' => 'required',
            'address' => 'required',
            'company_id' => 'required | exists:companies,id'
        ]);

        $contact = Contact::findOrFail($id);
        $contact->update($request);

        return redirect()->route('contacts.index')->with('message', "Contact has been updated successfully");
    }

    public function show($id)
    {
        $contact = Contact::find($id);
        return view('contacts.show', compact('contact'));
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return back()->with('message', "Contact has been deleted successfully");
    }

}
