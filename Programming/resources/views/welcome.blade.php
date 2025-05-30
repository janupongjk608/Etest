<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8" />
<title>ระบบจัดการโปรไฟล์และรายงาน</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<style>
  body {
    font-family: Arial, sans-serif;
    display: flex;
    max-width: 1100px;
    margin: 30px auto;
    gap: 40px;
  }
  /* ซ้าย: รายงาน */
  #reportSection {
    flex: 1;
    max-width: 500px;
  }
  #myChart {
    width: 100% !important;
    max-width: 600px;
    height: 300px !important;
  }
  table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 20px;
  }
  th, td {
    border: 1px solid #aaa;
    padding: 8px;
    text-align: center;
  }
  th {
    background-color: #eee;
  }

  /* ขวา: ฟอร์ม + รายการโปรไฟล์ */
  #manageSection {
    flex: 1;
    max-width: 500px;
  }
  form {
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 8px;
  }
  label {
    display: block;
    margin-top: 15px;
  }
  input, select {
    width: 100%;
    padding: 8px;
    margin-top: 6px;
    box-sizing: border-box;
  }
  button {
    margin-top: 20px;
    padding: 10px 15px;
    background-color: #007bff;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
    border-radius: 4px;
  }
  button:disabled {
    background-color: #aaa;
    cursor: not-allowed;
  }
  .message {
    margin-top: 10px;
    font-weight: bold;
  }
  .error {
    color: red;
  }
  .success {
    color: green;
  }
  /* ตารางรายการโปรไฟล์ */
  #profileList {
    margin-top: 30px;
    border-collapse: collapse;
    width: 100%;
  }
  #profileList th, #profileList td {
    border: 1px solid #aaa;
    padding: 6px;
    text-align: center;
  }
  #profileList th {
    background-color: #eee;
  }
  #profileList button {
    padding: 5px 8px;
    font-size: 14px;
    margin: 0 2px;
  }
  #profileList button.edit {
    background-color: #28a745;
    color: white;
  }
  #profileList button.delete {
    background-color: #dc3545;
    color: white;
  }
</style>
</head>
<body>

<!-- ซ้าย: รายงาน -->
<section id="reportSection">
  <h2>รายงานจำนวนสมาชิกตามช่วงอายุ</h2>
  <canvas id="myChart"></canvas>
  <table id="ageReportTable">
    <thead>
      <tr><th>ช่วงอายุ</th><th>จำนวนสมาชิก</th></tr>
    </thead>
    <tbody></tbody>
  </table>
</section>

<!-- ขวา: ฟอร์มจัดการโปรไฟล์ -->
<section id="manageSection">
  <h2>จัดการโปรไฟล์</h2>

  <form id="profileForm" enctype="multipart/form-data">
    <input type="hidden" id="profileId" name="id" />

    <label for="title">คำนำหน้า</label>
    <select id="title" name="title" required>
      <option value="">-- กรุณาเลือก --</option>
      <option value="นาย">นาย</option>
      <option value="นาง">นาง</option>
      <option value="นางสาว">นางสาว</option>
    </select>

    <label for="name">ชื่อ</label>
    <input type="text" id="name" name="name" required maxlength="255" />

    <label for="last_name">นามสกุล</label>
    <input type="text" id="last_name" name="last_name" required maxlength="255" />

    <label for="birth_date">วันเกิด</label>
    <input type="date" id="birth_date" name="birth_date" required />

    <label for="path_profile">รูปโปรไฟล์ (ถ้ามี)</label>
    <input type="file" id="path_profile" name="path_profile" accept="image/*" />

    <button type="submit" id="submitBtn">เพิ่มโปรไฟล์</button>
    <button type="button" id="cancelEditBtn" style="display:none; margin-left: 10px;">ยกเลิก</button>
  </form>

  <div class="message" id="message"></div>

  <h3 style="margin-top: 40px;">รายการโปรไฟล์</h3>
  <table id="profileList">
    <thead>
      <tr>
        <th>คำนำหน้า</th>
        <th>ชื่อ</th>
        <th>นามสกุล</th>
        <th>วันเกิด</th>
        <th>รูปโปรไฟล์</th>
        <th>จัดการ</th>
      </tr>
    </thead>
    <tbody>
      <!-- รายการโปรไฟล์จะถูกเติมด้วย JS -->
    </tbody>
  </table>
</section>

<script>
  // --- ส่วนรายงาน ---
  async function loadAgeReport() {
    try {
      const res = await fetch('/api/profiles/age-report');
      const data = await res.json();

      // เตรียมข้อมูลกราฟ
      const xValues = data.map(item => item.age_range);
      const yValues = data.map(item => item.count);
      const barColors = xValues.map(() => 'rgba(54, 162, 235, 0.7)');

      // สร้างกราฟ
      new Chart("myChart", {
        type: "bar",
        data: {
          labels: xValues,
          datasets: [{
            backgroundColor: barColors,
            data: yValues
          }]
        },
        options: {
          legend: { display: false },
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true,
                stepSize: 1,
                max: 20,
                precision: 0
              }
            }]
          },
          title: {
            display: true,
            text: "จำนวนสมาชิกตามช่วงอายุ"
          }
        }
      });

      // เติมตาราง
      const tbody = document.querySelector("#ageReportTable tbody");
      tbody.innerHTML = '';
      data.forEach(item => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${item.age_range}</td><td>${item.count}</td>`;
        tbody.appendChild(tr);
      });
    } catch(e) {
      console.error('Error loading age report:', e);
    }
  }


  // --- ส่วนจัดการโปรไฟล์ ---

  const form = document.getElementById('profileForm');
  const messageDiv = document.getElementById('message');
  const profileListTbody = document.querySelector('#profileList tbody');
  const submitBtn = document.getElementById('submitBtn');
  const cancelEditBtn = document.getElementById('cancelEditBtn');

  // โหลดรายชื่อโปรไฟล์ทั้งหมด (GET)
  async function loadProfiles() {
    try {
      const res = await fetch('/api/profiles');
      if (!res.ok) throw new Error('ไม่สามารถโหลดข้อมูลโปรไฟล์ได้');
      const profiles = await res.json();
      profileListTbody.innerHTML = '';
      profiles.forEach(p => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${p.title}</td>
          <td>${p.name}</td>
          <td>${p.last_name}</td>
          <td>${p.birth_date}</td>
          <td>${p.path_profile ? `<img src="${p.path_profile}" alt="profile" style="height:40px; border-radius:4px;" />` : '-'}</td>
          <td>
            <button class="edit" data-id="${p.id}">แก้ไข</button>
            <button class="delete" data-id="${p.id}">ลบ</button>
          </td>
        `;
        profileListTbody.appendChild(tr);
      });

      // เพิ่ม event listener ปุ่มแก้ไข/ลบ
      profileListTbody.querySelectorAll('button.edit').forEach(btn => {
        btn.addEventListener('click', () => editProfile(btn.dataset.id));
      });
      profileListTbody.querySelectorAll('button.delete').forEach(btn => {
        btn.addEventListener('click', () => deleteProfile(btn.dataset.id));
      });
    } catch (e) {
      showMessage('error', e.message);
    }
  }

  // แสดงข้อความ
  function showMessage(type, text) {
    messageDiv.textContent = text;
    messageDiv.className = 'message ' + (type === 'error' ? 'error' : 'success');
  }

  // รีเซ็ตฟอร์ม
  function resetForm() {
    form.reset();
    form.profileId.value = '';
    submitBtn.textContent = 'เพิ่มโปรไฟล์';
    cancelEditBtn.style.display = 'none';
    messageDiv.textContent = '';
    messageDiv.className = 'message';
  }

  // ส่งข้อมูลเพิ่มหรือแก้ไขโปรไฟล์
  form.addEventListener('submit', async e => {
    e.preventDefault();
    messageDiv.textContent = '';
    messageDiv.className = 'message';

    const profileId = form.profileId.value;
    const formData = new FormData(form);

    // ถ้าแก้ไขและไม่มีไฟล์ใหม่ จะไม่ส่งฟิลด์ path_profile (เพื่อไม่ลบไฟล์เดิม)
    if (!form.path_profile.files.length) {
      formData.delete('path_profile');
    }

    try {
      let url = '/api/profiles';
      let method = 'POST';
      if (profileId) {
        url += '/' + profileId;
        method = 'PUT';
      }

      const res = await fetch(url, {
        method,
        body: formData,
      });

      if (!res.ok) {
        const errorData = await res.json();
        let errors = [];
        if (errorData.errors) {
          for (const key in errorData.errors) {
            errors.push(errorData.errors[key].join(', '));
          }
        } else if (errorData.message) {
          errors.push(errorData.message);
        }
        throw new Error(errors.join('; '));
      }

      showMessage('success', profileId ? 'แก้ไขโปรไฟล์สำเร็จ' : 'เพิ่มโปรไฟล์สำเร็จ');
      resetForm();
      loadProfiles();
      loadAgeReport();
    } catch (err) {
      showMessage('error', err.message);
    }
  });

  // กดปุ่มยกเลิกแก้ไข
  cancelEditBtn.addEventListener('click', resetForm);

  // ดึงข้อมูลโปรไฟล์มาใส่ในฟอร์มเพื่อแก้ไข
  async function editProfile(id) {
    try {
      const res = await fetch('/api/profiles/' + id);
      if (!res.ok) throw new Error('ไม่พบข้อมูลโปรไฟล์นี้');
      const profile = await res.json();

      form.profileId.value = profile.id;
      form.title.value = profile.title;
      form.name.value = profile.name;
      form.last_name.value = profile.last_name;
      form.birth_date.value = profile.birth_date;
      form.path_profile.value = ''; // ไฟล์ต้องเลือกใหม่หากต้องการเปลี่ยน

      submitBtn.textContent = 'บันทึกการแก้ไข';
      cancelEditBtn.style.display = 'inline-block';
      messageDiv.textContent = '';
      messageDiv.className = 'message';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    } catch (e) {
      showMessage('error', e.message);
    }
  }

  // ลบโปรไฟล์
  async function deleteProfile(id) {
    if (!confirm('ยืนยันการลบโปรไฟล์นี้?')) return;
    try {
      const res = await fetch('/api/profiles/' + id, { method: 'DELETE' });
      if (!res.ok) {
        const errorData = await res.json();
        throw new Error(errorData.message || 'ลบไม่สำเร็จ');
      }
      showMessage('success', 'ลบโปรไฟล์สำเร็จ');
      loadProfiles();
      loadAgeReport();
    } catch (e) {
      showMessage('error', e.message);
    }
  }

  // โหลดข้อมูลเริ่มต้น
  loadProfiles();
  loadAgeReport();

</script>

</body>
</html>
