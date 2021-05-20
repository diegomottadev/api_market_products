<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getTokens(){
        return view('home.personal-tokens');
    }

    public function getClients(){
        return view('home.personal-clients');
    }

    public function getAuthorizedClients(){
        return view('home.authorized-clients');
    }
}


/*

eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI1IiwianRpIjoiNjMzMTUyYTI0NmRlNTU2ZGQyZWVlYjFmODMxZmY3OTQ0MDMwZjNkNGU0NmI0NjkwYmFhNWVmOWJlYzQ2NmIyZDFjNzdhZjRjNTgyMjBjNmIiLCJpYXQiOjE2MjE1Mjc1NTIuMDkwNjIyLCJuYmYiOjE2MjE1Mjc1NTIuMDkwNjMyLCJleHAiOjE2NTMwNjM1NTIuMDQ3MzgsInN1YiI6Ijk3Iiwic2NvcGVzIjpbXX0.IDUOAxkB97iBcbyBC2TdtwqSB4kZR4P658USuwk17oVXi08eJIhfP2Nwz2PrdJZKP8Jrqa2X5aNf-PS2jn3nx6dsVC6Nl3FX3ArC_DRKmSNyQIMj_5WMzfsOd8spPkGLHqFRlioZp-4lC8yR7_6GJcXABglO1ocQZJXn8XcbqSQBBifHVIACCMV8ekGNl7OjEZ_g44xtyXlFjjBv4Meo9w5gmiLLo1eG7HOnr6YfOq9FzvKMk1VmwgkB5PG-pJJmPrJsvzuWQ8Q18IWcYmeueLr754m1zbp1Jplg-jecuYrMzUcaQhw3LrBNaL7M9utdocjB_jSBvb8JOVS5mkv-GEsaKSexyWF6kZOcuVbgdFxyDGGfl-Bv_3SsQcp5nSWyKOn2rq-rMs0DxuWerJ9RMUnmq8wmdigP3OfknEZzHmPJr3IuDK-6heeSn6KpYTzw5z7N60Mt7DGAcrermubGX7l0Yr5RDdcvzfAhFDT0YWw82kcTEh_qg3zyAgH-dbjN-a8P4VhtL4ZLS4G3-c14CHcQMRY06LNdd2Ef_rzOSwLsRCr9vSH953o-zBFCaFptRg1USa9EzLdLD4VVolVjxYFuQPpKLdHl0PZkQ3fA9IQWck92Hn0Q739O6YWobSEW6drVCzqN6dvp4qRbsNGDQo8HHjVIG4agmuk57TD7yHE


/*
