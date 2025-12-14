<div class="form-row">
    <div class="form-group">
        <label for="nama">Nama Narasumber <span class="required">*</span></label>
        <input type="text" name="nama" id="nama" required placeholder="Masukkan nama narasumber"
            value="<?php echo isset($data) ? htmlspecialchars($data['nama']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="asal">Asal/Instansi</label>
        <input type="text" name="asal" id="asal" placeholder="Contoh: Universitas ABC"
            value="<?php echo isset($data) ? htmlspecialchars($data['asal'] ?? '') : ''; ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="no_hp">No. HP</label>
        <input type="text" name="no_hp" id="no_hp" placeholder="Contoh: 081234567890"
            value="<?php echo isset($data) ? htmlspecialchars($data['no_hp'] ?? '') : ''; ?>">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Contoh: narasumber@email.com"
            value="<?php echo isset($data) ? htmlspecialchars($data['email'] ?? '') : ''; ?>">
    </div>
</div>
