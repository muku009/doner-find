<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Lifesaver — Blood Donor Portal</title>
  <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Minimal CSS -->
  <style>
  html,body {height:100%;margin:0;font-family:Arial, Helvetica, sans-serif;}

  /* Responsive background using picture */
  .bg {
    position: relative;
    height: 100%;
    display:flex;
    align-items:center;
    justify-content:center;
  }
  .bg::before {
    content:'';
    position:absolute;
    inset:0;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    z-index:1;
    /* default background for desktop */
    background-image: url('{{ asset('/img/bg.jpg') }}');
  }
  /* Mobile background */
  @media (max-width:768px){
    .bg::before {
      background-image: url('{{ asset('/img/bg-mobile.jpg') }}');
    }
  }

  .overlay {
    background: rgba(0,0,0,0.45);
    position:absolute;
    inset:0;
    z-index:2;
  }

  .container {
    position:relative;
    z-index:3;
    width:100%;
    max-width:900px;
    padding:2rem;
    color:#fff;
    text-align:center;
  }

  h1 {font-size:2.6rem; margin-bottom:0.5rem;}
  p {margin-top:0;margin-bottom:1.5rem;opacity:0.95 }

  .buttons {display:flex;gap:1rem;justify-content:center;flex-wrap:wrap}
  .btn {
    background:#ff5252;border:none;padding:12px 22px;border-radius:8px;color:#fff;font-weight:600;cursor:pointer;
    box-shadow:0 6px 18px rgba(0,0,0,0.35);transition:transform .12s;
  }
  .btn.secondary {background:#1976d2;}
  .btn:active{transform:translateY(2px)}

  .modal { position:fixed;inset:0;display:none;align-items:center;justify-content:center;padding:20px; z-index:9999; }
  .modal.show {display:flex}
  .card {
    width:100%; max-width:620px; background:rgba(255,255,255,0.98); color:#ff5252; border-radius:12px; padding:22px;
    box-shadow:12px 6px 19px 9px rgba(2, 6, 23, 0.3);
    position:relative;
  }
  .card h2 {margin-top:0}
  .form-row {display:flex;gap:10px;margin-bottom:12px}
  .form-row .col {flex:1;display:flex;flex-direction:column}
  label {
    display: block;
    text-align: left;
    font-weight: bold;
    font-size: 0.85rem;
    margin-bottom: 6px;
  }
  input,select,textarea { padding:10px;border-radius:8px;border:1px solid #cfcfcf;font-size:1rem; }
  textarea {resize:vertical;min-height:70px}
  .card .actions {display:flex;gap:10px;justify-content:flex-end;margin-top:10px}
  .results {background:#fff;border-radius:8px;padding:12px;margin-top:14px; color:#111}
  .results table {width:100%;border-collapse:collapse;margin-top:8px;}
  .results th, .results td {border:1px solid #ddd;padding:8px;text-align:left;}
  .results th {background:#f2f2f2;}
  .small {font-size:0.9rem;color:#666}

  /* Beating heart */
  .beating-heart {
    display: inline-block;
    vertical-align: middle;
    animation: beat 1s infinite;
    width: 40px;
    height: 40px;
  }
  /* Responsive heart size */
  @media (max-width:768px){
    .beating-heart { width:30px; height:30px; }
    h1 { font-size:1.8rem; }
    p { font-size:0.9rem; }
  }
  @keyframes beat {
    0%, 100% { transform: scale(1); }
    25% { transform: scale(1.2); }
    50% { transform: scale(1); }
    75% { transform: scale(1.2); }
  }

  /* Form responsive */
  @media (max-width:600px){
    .form-row {flex-direction:column}
  }


  /*scroller table */
  <!-- inside your existing <style> tag -->

  /* existing CSS ... */

  /* Make donor results table scrollable */
  .table-container {
    max-height: 300px;  /* adjust height as needed */
    overflow-y: auto;
    overflow-x: auto;
    border: 1px solid #ddd;
    border-radius: 6px;
  }

  .results table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 8px;
  }

  /* rest of your CSS remains unchanged */










</style>

</head>
<body>
  <div class="bg">
    <div class="overlay"></div>
    <div class="container">
     <h1>
<h1>
  <img src="img/heart.png" class="beating-heart" alt="heart">
  Lifesaver
</h1>
  Lifesaver
</h1>



      <p>Register as a donor or search for a donor by blood group & city. Your small act can save lives.</p>

      <div class="buttons">
        <button class="btn" id="openRegister">Register Donor</button>
        <button class="btn secondary" id="openSearch">Search Donor</button>
      </div>

      <!-- Registration Modal -->
      <div class="modal" id="registerModal" aria-hidden="true">
        <div class="card">
          <h2>Register Donor</h2>
          <form id="registerForm">
            <div class="form-row">
              <div class="col">
                <label>Donor Name</label>
                <input type="text" name="name" required>
              </div>
              <div class="col">
                <label>Blood Group</label>
                <select name="blood_group" required>
                  <option value="">Select</option>
                  <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
                  <option>AB+</option><option>AB-</option><option>O+</option><option>O-</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="col">
                <label>State</label>
                <select name="state" id="regState" required></select>
              </div>
              <div class="col">
                <label>City</label>
                <select name="city" id="regCity" required><option value="">Select state first</option></select>
              </div>
            </div>

            <div class="form-row">
              <div class="col">
                <label>Mobile Number</label>
                <input type="text" name="mobile" required>
              </div>
              <div class="col">
                <label>Address</label>
                <input type="text" name="address">
              </div>
            </div>

            <div class="actions">
              <button type="button" class="btn" id="submitRegister">Register</button>
              <button type="button" class="btn secondary" id="cancelRegister">Cancel</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Search Modal -->
      <div class="modal" id="searchModal" aria-hidden="true">
        <div class="card">
          <h2>Search Donor</h2>
          <div class="form-row">
            <div class="col">
              <label>Blood Group</label>
              <select id="searchBlood">
                <option value="">Any</option>
                <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
                <option>AB+</option><option>AB-</option><option>O+</option><option>O-</option>
              </select>
            </div>
            <div class="col">
              <label>State</label>
              <select id="searchState"></select>
            </div>
            <div class="col">
              <label>City</label>
              <select id="searchCity"><option value="">Select state first</option></select>
            </div>
          </div>

          <div class="actions">
            <button class="btn" id="doSearch">Search</button>
            <button class="btn secondary" id="cancelSearch">Cancel</button>
          </div>

          <div id="searchResults" class="results" style="display:none">
  <h3>Results</h3>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Donor Name</th>
          <th>Blood Group</th>
          <th>City</th>
          <th>State</th>
          <th>Mobile Number</th>
        </tr>
      </thead>
      <tbody id="resultsList"></tbody>
    </table>
  </div>
</div>

        </div>
      </div>

    </div>
  </div>

  <!-- CSRF token for AJAX -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <script>
    const statesAndCities = {
      "Andhra Pradesh": ["Vijayawada","Visakhapatnam","Guntur","Tirupati","Rajahmundry"],
      "Arunachal Pradesh": ["Itanagar","Tawang","Ziro","Pasighat"],
      "Assam": ["Guwahati","Jorhat","Dibrugarh","Silchar"],
      "Bihar": ["Patna","Gaya","Bhagalpur","Muzaffarpur"],
      "Chhattisgarh": ["Raipur","Bhilai","Korba","Bilaspur"],
      "Goa": ["Panaji","Margao","Vasco da Gama"],
      "Gujarat": ["Ahmedabad","Surat","Vadodara","Rajkot"],
      "Haryana": ["Gurgaon","Faridabad","Panipat","Ambala"],
      "Himachal Pradesh": ["Shimla","Dharamshala","Solan"],
      "Jharkhand": ["Ranchi","Jamshedpur","Dhanbad"],
      "Karnataka": ["Bengaluru","Mysore","Mangalore","Hubli"],
      "Kerala": ["Thiruvananthapuram","Kochi","Kozhikode","Thrissur"],
      "Madhya Pradesh": ["Bhopal","Indore","Gwalior","Jabalpur"],
      "Maharashtra": ["Mumbai","Pune","Nagpur","Nashik"],
      "Manipur": ["Imphal"], "Meghalaya": ["Shillong"], "Mizoram": ["Aizawl"],
      "Nagaland": ["Kohima","Dimapur"], "Odisha": ["Bhubaneswar","Cuttack","Rourkela"],
      "Punjab": ["Chandigarh","Ludhiana","Amritsar","Jalandhar"],
      "Rajasthan": ["Jaipur","Jodhpur","Udaipur","Kota","Bhilwara"], "Sikkim": ["Gangtok"],
      "Tamil Nadu": ["Chennai","Coimbatore","Madurai","Tiruchirappalli"],
      "Telangana": ["Hyderabad","Warangal","Nizamabad"], "Tripura": ["Agartala"],
      "Uttar Pradesh": ["Lucknow","Kanpur","Varanasi","Agra"],
      "Uttarakhand": ["Dehradun","Haridwar","Nainital"], "West Bengal": ["Kolkata","Howrah","Durgapur","Asansol"]
    };

    function populateStates() {
      const stateSelects = [document.getElementById('regState'), document.getElementById('searchState')];
      const stateNames = Object.keys(statesAndCities);
      stateSelects.forEach(select => {
        select.innerHTML = '<option value="">Select state</option>';
        stateNames.forEach(s => { const opt = document.createElement('option'); opt.value = s; opt.textContent = s; select.appendChild(opt); });
      });
    }

    function wireStateCityPairs() {
      document.getElementById('regState').addEventListener('change', function(){ fillCity('regCity', this.value); });
      document.getElementById('searchState').addEventListener('change', function(){ fillCity('searchCity', this.value, true); });
    }

    function fillCity(citySelectId, stateValue, includeAny=false) {
      const sel = document.getElementById(citySelectId);
      sel.innerHTML = '';
      if (!stateValue || !statesAndCities[stateValue]) { sel.innerHTML = '<option value="">Select state first</option>'; return; }
      if (includeAny) { const anyOpt = document.createElement('option'); anyOpt.value=''; anyOpt.textContent='Any'; sel.appendChild(anyOpt); }
      else { sel.innerHTML = '<option value="">Select</option>'; }
      statesAndCities[stateValue].forEach(city=>{ const opt = document.createElement('option'); opt.value=city; opt.textContent=city; sel.appendChild(opt); });
    }

    const registerModal = document.getElementById('registerModal');
    const searchModal = document.getElementById('searchModal');

    document.getElementById('openRegister').addEventListener('click', ()=> { registerModal.classList.add('show'); });
    document.getElementById('cancelRegister').addEventListener('click', ()=> { registerModal.classList.remove('show'); document.getElementById('registerForm').reset(); });
    document.getElementById('openSearch').addEventListener('click', ()=> { searchModal.classList.add('show'); });
    document.getElementById('cancelSearch').addEventListener('click', ()=> { searchModal.classList.remove('show'); document.getElementById('searchResults').style.display = 'none'; });

    function getCsrfToken() { return document.querySelector('meta[name="csrf-token"]').getAttribute('content'); }

    document.getElementById('submitRegister').addEventListener('click', async function(){
      const form = document.getElementById('registerForm');
      const data = new FormData(form);
      if (!data.get('name') || !data.get('blood_group') || !data.get('state') || !data.get('city') || !data.get('mobile')) {
        Swal.fire('Missing fields','Please fill all required fields','warning'); return;
      }
      try {
        const res = await fetch("{{ route('donors.store') }}", { method: 'POST', headers: {'X-CSRF-TOKEN': getCsrfToken()}, body: data });
        const json = await res.json();
        if (res.status === 200 && json.status === 'success') {
          registerModal.classList.remove('show'); form.reset();
          Swal.fire({title: 'Thank you — you are a lifesaver! 😊', icon: 'success', timer: 2400, showConfirmButton: false});
        } else {
          let msg = 'Something went wrong';
          if (json.errors) { msg = Object.values(json.errors).flat().join('<br>'); }
          else if (json.message) { msg = json.message; }
          Swal.fire('Error', msg, 'error');
        }
      } catch (err) { console.error(err); Swal.fire('Error','Could not register. Try again.','error'); }
    });

    // Updated search results display in table
    document.getElementById('doSearch').addEventListener('click', async function(){
      const blood = document.getElementById('searchBlood').value;
      const state = document.getElementById('searchState').value;
      const city = document.getElementById('searchCity').value;
      const fd = new FormData();
      if (blood) fd.append('blood_group', blood);
      if (state) fd.append('state', state);
      if (city) fd.append('city', city);

      try {
        const res = await fetch("{{ route('donors.search') }}", { method: 'POST', headers: {'X-CSRF-TOKEN': getCsrfToken()}, body: fd });
        const json = await res.json();
        const list = document.getElementById('resultsList');
        list.innerHTML = '';
        if (json.status === 'success') {
          if (json.results.length === 0) { list.innerHTML = '<tr><td colspan="5" class="small">No donors found.</td></tr>'; }
          else {
            json.results.forEach(r => {
              const tr = document.createElement('tr');
              tr.innerHTML = `<td>${r.name}</td><td>${r.blood_group}</td><td>${r.city}</td><td>${r.state}</td><td>${r.mobile}</td>`;
              list.appendChild(tr);
            });
          }
          document.getElementById('searchResults').style.display = 'block';
        } else { Swal.fire('Error','Search failed','error'); }
      } catch (err) { console.error(err); Swal.fire('Error','Search failed','error'); }
    });

    populateStates(); wireStateCityPairs();
  </script>
</body>
</html>  
