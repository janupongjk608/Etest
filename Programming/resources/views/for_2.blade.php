<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Temple by Region</title>
  <style>
    body {
      font-family: sans-serif;
      background: #fdfdfd;
      margin: 0;
      padding: 20px;
      text-align: center;
    }

    h1 {
      font-size: 32px;
      margin-bottom: 5px;
    }

    p.subtitle {
      font-size: 16px;
      color: #666;
      margin-bottom: 30px;
    }

    .regions {
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
    }

    .region-card {
      width: 300px;
      height: 300px;
      position: relative;
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .region-card img {
      width: 300px;
      height: 300px;
      object-fit: cover;
    }

    .region-label {
      position: absolute;
      bottom: 0;
      width: 100%;
      background: rgba(0, 0, 0, 0.5);
      color: white;
      font-size: 18px;
      padding: 10px;
      box-sizing: border-box;
    }
  </style>
</head>
<body>

  <h1>TEMPLE BY REGION</h1>
  <p class="subtitle">ค้นหาข้อมูลวัดตามภูมิภาค</p>

  <div class="regions">
    <div class="region-card">
      <img src="{{ asset('storage/profiles/tem1.jpg') }}" alt="ภาคเหนือ" width="300" height="300">

      <div class="region-label">ภาคเหนือ</div>
    </div>
    <div class="region-card">
      <img src="{{ asset('storage/profiles/tem2.jpg') }}" alt="ภาคเหนือ" width="300" height="300">

      <div class="region-label">ภาคกลาง</div>
    </div>
    <div class="region-card">
      <img src="{{ asset('storage/profiles/tem3.jpg') }}" alt="ภาคเหนือ" width="300" height="300">

      <div class="region-label">ภาคตะวันออกเฉียงเหนือ</div>
    </div>
    <div class="region-card">
      <img src="{{ asset('storage/profiles/tem4.jpg') }}" alt="ภาคเหนือ" width="300" height="300">

      <div class="region-label">ภาคตะวันตก</div>
    </div>
  </div>

</body>
</html>