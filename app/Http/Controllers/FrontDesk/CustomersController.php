<?php

namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Customer; // Eloquent model for 'customers'
use Illuminate\Support\Facades\Auth;

class CustomersController extends Controller
{
    public function index()
    {
        // $userRole = Auth::user()->user_role;
        $statesAndLgas = $this->returnStateAndLga();

        // Retrieve all visible customers
        $customers = Customer::where('cust_view', '!=', 'hidden')->get();
        $states = array_keys($statesAndLgas);

        return view('Customers.index', compact('customers', 'states', 'statesAndLgas'));
    }

    public function getLga($state){
        // Define the states and their LGAs
        $statesAndLgas = $this->returnStateAndLga();
        // Check if the state exists in the array
        if (array_key_exists($state, $statesAndLgas)) {
            return response()->json($statesAndLgas[$state]);
        }

        // If state not found, return an error response
        return response()->json(['error' => 'State not found'], 404);
    }

    public function store(Request $request)
    {
        $rules = [
            'mode_of_contact' => 'required|string|in:email,sms|max:255',
            'account_type' => 'required|string|in:individual,corporate|max:255',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|digits:11',
            'email' => 'nullable|email|max:255|unique:customers,cust_email',
            'address' => 'required|string|max:255',
            'lga' => 'required|string',
            'state' => 'required|string',
        ];

        $customMessages = [
            'phone_number.digits' => 'Phone number must be exactly 11 digits.',
            'email.unique' => 'This email is already registered.',
        ];

        try {
            $validatedData = Validator::make($request->all(), $rules, $customMessages)->validate();

            Log::info('Customer data validated successfully', ['data' => $validatedData]);

            // Create a new customer using Eloquent
            Customer::create([
                'cust_name' => $validatedData['full_name'],
                'cust_mobile' => $validatedData['phone_number'],
                'cust_email' => $request->has('email_not_available') ? null : $validatedData['email'],
                'cust_address' => $validatedData['address'],
                'cust_lga' => $validatedData['lga'].' - ' .$validatedData['state'],
                'cust_mode' => $validatedData['mode_of_contact'],
                'cust_type' => $validatedData['account_type'],
                'cust_reg_time' => time(),
                'cust_asset' => 0,
                'cust_view' => 'visible',
            ]);

            Log::info('Customer data added successfully', ['email' => $validatedData['email']]);

            return redirect()->back()->with('success', 'Customer added successfully!');
        } catch (ValidationException $e) {
            Log::warning('Validation failed for customer data', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Failed to add customer: ' . $e->getMessage(), ['request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Failed to add customer. Please try again.');
        }
    }

    public function deleteCustomer($customerId)
    {
        $customer = Customer::find($customerId);

        if ($customer) {
            $customer->update(['cust_view' => 'hidden']);
            Log::info('Customer view updated to hidden', ['customer_id' => $customerId]);
            return redirect()->back()->with('success', 'Customer deleted successfully!');
        } else {
            Log::warning('Customer not found', ['customer_id' => $customerId]);
            return redirect()->back()->with('error', 'Customer not found!');
        }
    }

    public function edit($id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json(['error' => 'Customer not found'], 404);
            }

            Log::info('Customer data retrieved for editing', ['customer_id' => $id]);

            return response()->json($customer);
        } catch (\Exception $e) {
            Log::error('Error retrieving customer data', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching the customer data'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255', // Email is now nullable
            'phone_number' => 'required|string|max:11',
            'address' => 'required|string|max:255',
            'lga' => 'required|string|max:255',
        ]);

        try {
            $customer = Customer::find($id);

            if ($customer) {
                $customer->update([
                    'cust_name' => $request->input('full_name'),
                    'cust_email' => $request->has('email_not_available') ? null : $request->input('email'),
                    'cust_mobile' => $request->input('phone_number'),
                    'cust_address' => $request->input('address'),
                    'cust_lga' => $request->input('lga'),
                ]);

                Log::info('Customer updated successfully', ['customer_id' => $id]);

                return redirect()->back()->with('success', 'Customer updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Customer not found!');
            }
        } catch (\Exception $e) {
            Log::error('Error updating customer', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while updating the customer.');
        }
    }

    public function searchCustomers(Request $request)
    {
        $query = $request->get('search');

        // Validate the search query
        if (!$query) {
            return response()->json([], 400);
        }

        // Fetch customers matching the search term, including their vehicles
        $customers = Customer::where('cust_name', 'LIKE', '%' . $query . '%')
            ->with('vehicles') // Ensure the 'vehicles' relationship exists in the Customer model
            ->get();

        return response()->json($customers);
    }

    private function returnStateAndLga(){
        return $statesAndLgas = [
            'Abia' => ['Aba North', 'Aba South', 'Umuahia North', 'Umuahia South', 'Ohafia', 'Bende', 'Isuikwuato', 'Arochukwu', 'Obi Ngwa', 'Ikwuano', 'Ukwa West', 'Ukwa East'],
            'Adamawa' => ['Yola North', 'Yola South', 'Mubi North', 'Mubi South', 'Numan', 'Michika', 'Gombi', 'Mayo-Belwa', 'Ganye', 'Madagali', 'Hong', 'Guyuk', 'Lamurde', 'Shelleng', 'Demsa', 'Jada', 'Song', 'Toungo'],
            'Akwa Ibom' => ['Uyo', 'Eket', 'Ikot Ekpene', 'Oron', 'Abak', 'Ikot Abasi', 'Essien Udim', 'Oruk Anam', 'Mkpat Enin', 'Ikono', 'Itu', 'Ibeno', 'Onna', 'Nsit Ibom', 'Nsit Ubium', 'Ini', 'Okobo', 'Urue-Offong/Oruko', 'Etinan', 'Eastern Obolo', 'Ibiono Ibom', 'Mbo', 'Nsit Atai', 'Obot Akara', 'Udung-Uko'],
            'Anambra' => ['Awka South', 'Awka North', 'Onitsha South', 'Onitsha North', 'Idemili North', 'Idemili South', 'Nnewi North', 'Nnewi South', 'Ekwusigo', 'Ogbaru', 'Dunukofia', 'Orumba North', 'Orumba South', 'Aguata', 'Ihiala', 'Anambra East', 'Anambra West', 'Oyi'],
            'Bauchi' => ['Bauchi', 'Bogoro', 'Dass', 'Darazo', 'Katagum', 'Alkaleri', 'Misau', 'Ningi', 'Shira', 'Itas/Gadau', 'Giade', 'Tafawa Balewa', 'Toro', 'Warji', 'Zaki', 'Gamawa', 'Kirfi'],
            'Bayelsa' => ['Yenagoa', 'Kolokuma/Opokuma', 'Ogbia', 'Sagbama', 'Southern Ijaw', 'Ekeremor', 'Brass', 'Nembe'],
            'Benue' => ['Makurdi', 'Gboko', 'Otukpo', 'Oju', 'Katsina-Ala', 'Vandeikya', 'Tarka', 'Logo', 'Ukum', 'Apa', 'Agatu', 'Ogbadibo', 'Oturkpo', 'Ohimini', 'Obi', 'Konshisha', 'Kwande', 'Ado', 'Gwer East', 'Gwer West', 'Buruku'],
            'Borno' => ['Maiduguri', 'Jere', 'Bama', 'Gwoza', 'Dikwa', 'Kukawa', 'Ngala', 'Askira/Uba', 'Konduga', 'Shani', 'Monguno', 'Marte', 'Kwaya Kusar', 'Biu', 'Chibok', 'Hawul', 'Kala/Balge', 'Damboa', 'Gubio', 'Guzamala', 'Nganzai', 'Abadam', 'Mobbar'],
            'Cross River' => ['Calabar Municipal', 'Calabar South', 'Odukpani', 'Akpabuyo', 'Bakassi', 'Biase', 'Ikom', 'Obudu', 'Yala', 'Obubra', 'Ogoja', 'Akamkpa', 'Etung', 'Abi', 'Boki', 'Bekwarra'],
            'Delta' => ['Warri South', 'Warri North', 'Warri South-West', 'Ughelli North', 'Ughelli South', 'Uvwie', 'Okpe', 'Ethiope West', 'Ethiope East', 'Sapele', 'Ndokwa East', 'Ndokwa West', 'Isoko North', 'Isoko South', 'Bomadi', 'Patani', 'Aniocha North', 'Aniocha South', 'Oshimili North', 'Oshimili South', 'Burutu', 'Ukwuani'],
            'Ebonyi' => ['Abakaliki', 'Afikpo North', 'Afikpo South', 'Ebonyi', 'Ezza North', 'Ezza South', 'Ikwo', 'Ishielu', 'Ivo', 'Ohaozara', 'Ohaukwu', 'Onicha'],
            'Edo' => ['Benin City', 'Egor', 'Ikpoba-Okha', 'Oredo', 'Orhionmwon', 'Ovia North-East', 'Ovia South-West', 'Uhunmwonde', 'Esan North-East', 'Esan South-East', 'Esan Central', 'Esan West', 'Akoko-Edo', 'Etsako Central', 'Etsako East', 'Etsako West'],
            'Ekiti' => ['Ado-Ekiti', 'Ikere', 'Oye', 'Ikole', 'Irepodun/Ifelodun', 'Ijero', 'Gbonyin', 'Emure', 'Efon', 'Ekiti East', 'Ekiti South-West', 'Ekiti West', 'Ido/Osi', 'Moba'],
            'Enugu' => ['Enugu East', 'Enugu North', 'Enugu South', 'Nsukka', 'Udi', 'Awgu', 'Aninri', 'Ezeagu', 'Igbo-Eze North', 'Igbo-Eze South', 'Igbo-Etiti', 'Isi-Uzo', 'Nkanu East', 'Nkanu West', 'Oji River', 'Uzo-Uwani'],
            'FCT' => ['Abuja Municipal', 'Gwagwalada', 'Kuje', 'Bwari', 'Abaji', 'Kwali'],
            'Gombe' => ['Gombe', 'Akko', 'Billiri', 'Dukku', 'Funakaye', 'Kaltungo', 'Kwami', 'Nafada', 'Shongom', 'Yamaltu/Deba'],
            'Imo' => ['Owerri Municipal', 'Owerri North', 'Owerri West', 'Orlu', 'Okigwe', 'Mbaitoli', 'Ikeduru', 'Oguta', 'Ohaji/Egbema', 'Njaba', 'Isu', 'Ehime Mbano', 'Obowo', 'Ihitte/Uboma', 'Nkwerre', 'Nwangele', 'Ezinihitte', 'Oru East', 'Oru West'],
            'Jigawa' => ['Dutse', 'Kazaure', 'Hadejia', 'Birnin Kudu', 'Garki', 'Ringim', 'Gumel', 'Babura', 'Maigatari', 'Kiri Kasama', 'Kafin Hausa', 'Gwaram', 'Malam Madori', 'Roni', 'Buji', 'Jahun', 'Birniwa', 'Yankwashi', 'Guri', 'Taura'],
            'Kaduna' => ['Kaduna North', 'Kaduna South', 'Zaria', 'Chikun', 'Igabi', 'Jema\'a', 'Kagarko', 'Kaura', 'Kauru', 'Kubau', 'Kudan', 'Lere', 'Makarfi', 'Sabon Gari', 'Sanga', 'Soba', 'Zangon Kataf'],
            'Kano' => ['Dala', 'Fagge', 'Gwale', 'Kano Municipal', 'Kumbotso', 'Nasarawa', 'Tarauni', 'Ungogo', 'Bebeji', 'Gwarzo', 'Dawakin Tofa', 'Tofa', 'Gezawa', 'Rano', 'Kibiya', 'Kiru', 'Makoda', 'Warawa', 'Doguwa', 'Bunkure', 'Dambatta', 'Gaya', 'Wudil', 'Minjibir', 'Rimin Gado'],
            'Katsina' => ['Katsina', 'Daura', 'Funtua', 'Bakori', 'Batagarawa', 'Batsari', 'Baure', 'Bindawa', 'Charanchi', 'Dan Musa', 'Dandume', 'Danja', 'Daura', 'Dutsin Ma', 'Ingawa', 'Jibia', 'Kaita', 'Kankara', 'Kankia', 'Kurfi', 'Kusada', 'Mai\'Adua', 'Malumfashi', 'Mani', 'Mashi', 'Matazu', 'Musawa', 'Rimi', 'Sabuwa', 'Safana', 'Zango'],
            'Kebbi' => ['Birnin Kebbi', 'Argungu', 'Fakai', 'Arewa', 'Yauri', 'Zuru', 'Maiyama', 'Ngaski', 'Koko-Besse', 'Shanga', 'Bagudo', 'Dandi', 'Suru', 'Sakaba', 'Danko-Wasagu', 'Kalgo', 'Aliero', 'Bunza'],
            'Kogi' => ['Lokoja', 'Kogi', 'Bassa', 'Ajaokuta', 'Okene', 'Adavi', 'Okehi', 'Ogori/Magongo', 'Dekina', 'Ofu', 'Ibaji', 'Idah', 'Igalamela-Odolu', 'Omala', 'Yagba East', 'Yagba West', 'Mopa-Muro', 'Ijumu', 'Kabba/Bunu', 'Ankpa'],
            'Kwara' => ['Ilorin East', 'Ilorin South', 'Ilorin West', 'Asa', 'Offa', 'Oyun', 'Ifelodun', 'Edu', 'Kaiama', 'Moro', 'Isin', 'Patigi', 'Baruten', 'Ekiti'],
            'Lagos' => ['Agege', 'Alimosho', 'Amuwo-Odofin', 'Apapa', 'Badagry', 'Epe', 'Eti-Osa', 'Ibeju-Lekki', 'Ifako-Ijaiye', 'Ikeja', 'Ikorodu', 'Kosofe', 'Lagos Island', 'Lagos Mainland', 'Mushin', 'Ojo', 'Oshodi-Isolo', 'Shomolu', 'Surulere'],
            'Nasarawa' => ['Lafia', 'Karu', 'Keffi', 'Akwanga', 'Nasarawa', 'Obi', 'Toto', 'Nasarawa Eggon', 'Wamba', 'Doma', 'Awe', 'Kokona'],
            'Niger' => ['Chanchaga', 'Mokwa', 'Minna', 'Bida', 'Kontagora', 'Lapai', 'Agaie', 'Suleja', 'Rafi', 'Bosso', 'Wushishi', 'Lavun', 'Edati', 'Katcha', 'Magama', 'Mariga', 'Rijau', 'Shiroro', 'Tafa'],
            'Ogun' => ['Abeokuta North', 'Abeokuta South', 'Ewekoro', 'Ifo', 'Ijebu East', 'Ijebu North', 'Ijebu North East', 'Ijebu Ode', 'Ikene', 'Imeko Afon', 'Ipokia', 'Obafemi Owode', 'Odeda', 'Odogbolu', 'Ogun Waterside', 'Remo North', 'Sagamu', 'Yewa North', 'Yewa South'],
            'Ondo' => ['Akure South', 'Akure North', 'Owo', 'Okitipupa', 'Ondo West', 'Ondo East', 'Ilaje', 'Ese Odo', 'Idanre', 'Ifedore', 'Ose', 'Akoko North-East', 'Akoko North-West', 'Akoko South-East', 'Akoko South-West'],
            'Osun' => ['Osogbo', 'Ilesa East', 'Ilesa West', 'Ede North', 'Ede South', 'Iwo', 'Olorunda', 'Irepodun', 'Ejigbo', 'Atakunmosa East', 'Atakunmosa West', 'Ife Central', 'Ife East', 'Ife North', 'Ife South', 'Boluwaduro', 'Boripe', 'Ila', 'Ifelodun', 'Ayedaade', 'Ayedire', 'Irewole', 'Isokan', 'Odo Otin', 'Ola Oluwa', 'Oriade', 'Obokun', 'Egbedore'],
            'Oyo' => ['Ibadan North', 'Ibadan North-East', 'Ibadan North-West', 'Ibadan South-East', 'Ibadan South-West', 'Ogbomosho North', 'Ogbomosho South', 'Ibarapa Central', 'Ibarapa East', 'Ibarapa North', 'Kajola', 'Atiba', 'Atisbo', 'Egbeda', 'Irepo', 'Iseyin', 'Itesiwaju', 'Iwajowa', 'Lagelu', 'Ogo Oluwa', 'Olorunsogo', 'Oluyole', 'Ona Ara', 'Orelope', 'Ori Ire', 'Saki East', 'Saki West', 'Surulere'],
            'Plateau' => ['Jos North', 'Jos South', 'Jos East', 'Bassa', 'Bokkos', 'Barkin Ladi', 'Kanam', 'Kanke', 'Langtang North', 'Langtang South', 'Mangu', 'Pankshin', 'Qua\'an Pan', 'Riyom', 'Shendam', 'Wase', 'Mikang'],
            'Rivers' => ['Port Harcourt', 'Obio/Akpor', 'Okrika', 'Ogu/Bolo', 'Eleme', 'Tai', 'Gokana', 'Khana', 'Opobo/Nkoro', 'Andoni', 'Bonny', 'Asari-Toru', 'Akuku-Toru', 'Degema', 'Ahoada East', 'Ahoada West', 'Abua/Odual', 'Emohua', 'Ikwerre', 'Etche', 'Omuma'],
            'Sokoto' => ['Binji', 'Bodinga', 'Dange Shuni', 'Gada', 'Goronyo', 'Gudu', 'Gwadabawa', 'Illela','Isa', 'Kebbe', 'Kware', 'Rabah', 'Sabon Birni', 'Shagari', 'Silame', 'Sokoto North','Sokoto South', 'Tambuwal', 'Tangaza', 'Tureta', 'Wamako', 'Wurno', 'Yabo'],
            'Taraba' => ['Ardo Kola', 'Bali', 'Donga', 'Gashaka', 'Gassol', 'Ibi', 'Jalingo', 'Karim Lamido','Kurmi', 'Lau', 'Sardauna', 'Takum', 'Ussa', 'Wukari', 'Yorro', 'Zing'],
            'Yobe' => ['Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 'Geidam', 'Gujba', 'Gulani','Jakusko', 'Karasuwa', 'Machina', 'Nangere', 'Nguru', 'Potiskum', 'Tarmuwa', 'Yunusari', 'Yusufari'],
            'Zamfara' => ['Anka', 'Bakura', 'Birnin Magaji/Kiyaw', 'Bukkuyum', 'Bungudu', 'Gummi', 'Gusau','Kaura Namoda', 'Maradun', 'Maru', 'Shinkafi', 'Talata Mafara', 'Zurmi']
        ];
    }
}
