<?php
namespace App\Http\Controllers;
use App\Http\Requests\RequestCompanyAccountRequest;
use App\Models\RequestCompanyAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
class RequestCompanyAccountController extends Controller
{
    public function index(): View
    {
        return view('request-company-account');
    }
    public function saveCompanyAccountRequest(RequestCompanyAccountRequest $request)
    {
        RequestCompanyAccount::create([
            'subdomain' => $request->subdomain,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('home-page')
            ->with('success', 'Your company account request has been sent successfully');
    }
}
