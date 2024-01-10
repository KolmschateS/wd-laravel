<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Mail\ContactMailSubmitted;

// ...

class ContactController extends Controller
{
    public function submitForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'email' => 'required|email',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:600',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'email', 'subject', 'message']);
        $recipientEmail = 's1148925@student.windesheim.nl';

        // Send email using the Mailable
        Mail::to($recipientEmail)->send(new ContactMailSubmitted($data));

        return response()->json(['message' => 'Form submitted successfully']);
    }
}