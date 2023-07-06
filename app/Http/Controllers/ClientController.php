<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     *
     * @return view with clients filter by user
     */
    public function index()
    {
        $clients = Client::where('user_id', Auth::user()->id)->with('tasks')->latest()->paginate(10);

        return view('client.index')->with('clients', $clients);
    }


    /**
     * Show the form for creating a new client.
     *
     * @return view with countries
     */
    public function create()
    {
        return view('client.create')->with('countries', $this->countries_list);
    }

    /**
     * Store a newly created client in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Data Validation
        $request->validate([
            'name'  => ['required', 'max:255', 'string'],
            'username'  => ['required', 'max:255', 'string'],
            'email'  => ['required', 'max:255', 'string', 'email'],
            'phone'  => ['nullable','max:255', 'string'],
            'country'  => ['max:255', 'string', 'not_in:none'],
            'status'  => ['not_in:none', 'string'],
            'thumbnail'  => ['image'],
        ]);

        try {
            $thumb = null;
            if (!empty($request->file('thumbnail'))) {
                $thumb = time() . '-' . $request->file('thumbnail')->getClientOriginalName();
                $request->file('thumbnail')->storeAs('public/uploads', $thumb);
            }
            // Create new client
            Client::create([
                'name'       => $request->name,
                'username'   => $request->username,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'country'    => $request->country,
                'thumbnail'  => $thumb,
                'user_id'    => Auth::user()->id,
                'status'     => $request->status,
            ]);
            // Return Response
            return redirect()->route('client.index')->with('success', 'Client Added Successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('client.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified client.
     *
     * @param  \App\Models\Client $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        // Client with tasks and invoices
        $client = $client->load('tasks', 'invoices');



        // Return View
        return view('client.profile')->with([
            'client' => $client,
            'pending_tasks' => $client->tasks->where('status', 'pending'),
            'paid_invoices' => $client->invoices->where('status', 'paid'),
        ]);
    }

    /**
     * Show the form for editing the specified client.
     *
     * @param  \App\Models\Client $client
     * @return view with clients and countries
     */
    public function edit(Client $client)
    {
        return view('client.edit')->with([
            'client' => $client,
            'countries' => $this->countries_list,
        ]);
    }

    /**
     * Update the specified client in database.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Client $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        // Data Validation
        $request->validate([
            'name'  => ['required', 'max:255', 'string'],
            'username'  => ['required', 'max:255', 'string'],
            'email'  => ['required', 'max:255', 'string', 'email'],
            'phone'  => ['max:255', 'string'],
            'country'  => ['max:255', 'string', 'not_in:none'],
            'thumbnail'  => ['image'],
        ]);

        try {
            // Default thumbnail from database
            $thumb = $client->thumbnail;

            // Upload new thumbnail
            if (!empty($request->file('thumbnail'))) {
                Storage::delete('public/uploads/' . $thumb);
                $filename = strtolower(str_replace(' ', '-', $request->file('thumbnail')->getClientOriginalName()));
                $thumb    = time() . '-' . $filename;
                $request->file('thumbnail')->storeAs('public/uploads', $thumb);
            }

            // Update client data
            Client::find($client->id)->update([
                'name'       => $request->name,
                'username'   => $request->username,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'country'    => $request->country,
                'thumbnail'  => $thumb,
                'user_id'    => Auth::user()->id,
                'status'     => $request->status,
            ]);
            // Return Response
            return redirect()->route('client.index')->with('success', 'Client Updated');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('client.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified client from database.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        // find all pending tasks of the client
        $pending_tasks = $client->tasks->where('status', 'pending');

        try {
            // Soft delete or delete depending on the condition
            if (count($pending_tasks) == 0) {
                Storage::delete('public/uploads/' . $client->thumbnail);
                $client->delete();
                $message = 'Client deleted';
            } else {
                $client->update([
                    'status'    => 'inactive'
                ]);
                $message = 'Client is inactive!';
            }
            // Return Response
            return redirect()->route('client.index')->with('success', $message);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('client.index')->with('error', $th->getMessage());
        }
    }

    // Country List
    public $countries_list = array(
        "Afghanistan",
        "Aland Islands",
        "Albania",
        "Algeria",
        "American Samoa",
        "Andorra",
        "Angola",
        "Anguilla",
        "Antarctica",
        "Antigua and Barbuda",
        "Argentina",
        "Armenia",
        "Aruba",
        "Australia",
        "Austria",
        "Azerbaijan",
        "Bahamas",
        "Bahrain",
        "Bangladesh",
        "Barbados",
        "Belarus",
        "Belgium",
        "Belize",
        "Benin",
        "Bermuda",
        "Bhutan",
        "Bolivia",
        "Bonaire, Sint Eustatius and Saba",
        "Bosnia and Herzegovina",
        "Botswana",
        "Bouvet Island",
        "Brazil",
        "British Indian Ocean Territory",
        "Brunei Darussalam",
        "Bulgaria",
        "Burkina Faso",
        "Burundi",
        "Cambodia",
        "Cameroon",
        "Canada",
        "Cape Verde",
        "Cayman Islands",
        "Central African Republic",
        "Chad",
        "Chile",
        "China",
        "Christmas Island",
        "Cocos (Keeling) Islands",
        "Colombia",
        "Comoros",
        "Congo",
        "Congo, Democratic Republic of the Congo",
        "Cook Islands",
        "Costa Rica",
        "Cote D'Ivoire",
        "Croatia",
        "Cuba",
        "Curacao",
        "Cyprus",
        "Czech Republic",
        "Denmark",
        "Djibouti",
        "Dominica",
        "Dominican Republic",
        "Ecuador",
        "Egypt",
        "El Salvador",
        "Equatorial Guinea",
        "Eritrea",
        "Estonia",
        "Ethiopia",
        "Falkland Islands (Malvinas)",
        "Faroe Islands",
        "Fiji",
        "Finland",
        "France",
        "French Guiana",
        "French Polynesia",
        "French Southern Territories",
        "Gabon",
        "Gambia",
        "Georgia",
        "Germany",
        "Ghana",
        "Gibraltar",
        "Greece",
        "Greenland",
        "Grenada",
        "Guadeloupe",
        "Guam",
        "Guatemala",
        "Guernsey",
        "Guinea",
        "Guinea-Bissau",
        "Guyana",
        "Haiti",
        "Heard Island and Mcdonald Islands",
        "Holy See (Vatican City State)",
        "Honduras",
        "Hong Kong",
        "Hungary",
        "Iceland",
        "India",
        "Indonesia",
        "Iran, Islamic Republic of",
        "Iraq",
        "Ireland",
        "Isle of Man",
        "Israel",
        "Italy",
        "Jamaica",
        "Japan",
        "Jersey",
        "Jordan",
        "Kazakhstan",
        "Kenya",
        "Kiribati",
        "Korea, Democratic People's Republic of",
        "Korea, Republic of",
        "Kosovo",
        "Kuwait",
        "Kyrgyzstan",
        "Lao People's Democratic Republic",
        "Latvia",
        "Lebanon",
        "Lesotho",
        "Liberia",
        "Libyan Arab Jamahiriya",
        "Liechtenstein",
        "Lithuania",
        "Luxembourg",
        "Macao",
        "Macedonia, the Former Yugoslav Republic of",
        "Madagascar",
        "Malawi",
        "Malaysia",
        "Maldives",
        "Mali",
        "Malta",
        "Marshall Islands",
        "Martinique",
        "Mauritania",
        "Mauritius",
        "Mayotte",
        "Mexico",
        "Micronesia, Federated States of",
        "Moldova, Republic of",
        "Monaco",
        "Mongolia",
        "Montenegro",
        "Montserrat",
        "Morocco",
        "Mozambique",
        "Myanmar",
        "Namibia",
        "Nauru",
        "Nepal",
        "Netherlands",
        "Netherlands Antilles",
        "New Caledonia",
        "New Zealand",
        "Nicaragua",
        "Niger",
        "Nigeria",
        "Niue",
        "Norfolk Island",
        "Northern Mariana Islands",
        "Norway",
        "Oman",
        "Pakistan",
        "Palau",
        "Palestinian Territory, Occupied",
        "Panama",
        "Papua New Guinea",
        "Paraguay",
        "Peru",
        "Philippines",
        "Pitcairn",
        "Poland",
        "Portugal",
        "Puerto Rico",
        "Qatar",
        "Reunion",
        "Romania",
        "Russian Federation",
        "Rwanda",
        "Saint Barthelemy",
        "Saint Helena",
        "Saint Kitts and Nevis",
        "Saint Lucia",
        "Saint Martin",
        "Saint Pierre and Miquelon",
        "Saint Vincent and the Grenadines",
        "Samoa",
        "San Marino",
        "Sao Tome and Principe",
        "Saudi Arabia",
        "Senegal",
        "Serbia",
        "Serbia and Montenegro",
        "Seychelles",
        "Sierra Leone",
        "Singapore",
        "Sint Maarten",
        "Slovakia",
        "Slovenia",
        "Solomon Islands",
        "Somalia",
        "South Africa",
        "South Georgia and the South Sandwich Islands",
        "South Sudan",
        "Spain",
        "Sri Lanka",
        "Sudan",
        "Suriname",
        "Svalbard and Jan Mayen",
        "Swaziland",
        "Sweden",
        "Switzerland",
        "Syrian Arab Republic",
        "Taiwan, Province of China",
        "Tajikistan",
        "Tanzania, United Republic of",
        "Thailand",
        "Timor-Leste",
        "Togo",
        "Tokelau",
        "Tonga",
        "Trinidad and Tobago",
        "Tunisia",
        "Turkey",
        "Turkmenistan",
        "Turks and Caicos Islands",
        "Tuvalu",
        "Uganda",
        "Ukraine",
        "United Arab Emirates",
        "United Kingdom",
        "United States",
        "United States Minor Outlying Islands",
        "Uruguay",
        "Uzbekistan",
        "Vanuatu",
        "Venezuela",
        "Viet Nam",
        "Virgin Islands, British",
        "Virgin Islands, U.s.",
        "Wallis and Futuna",
        "Western Sahara",
        "Yemen",
        "Zambia",
        "Zimbabwe"
    );
}
