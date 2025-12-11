<div class="form-row">
    <div class="form-group">
        <label for="nama">Nama Lengkap <span class="required">*</span></label>
        <input type="text" name="nama" id="nama" required placeholder="Masukkan nama lengkap"
            value="<?php echo isset($data) ? htmlspecialchars($data['nama']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="no_hp">No HP</label>
        <input type="text" name="no_hp" id="no_hp" placeholder="Contoh: 081234567890"
            value="<?php echo isset($data) ? htmlspecialchars($data['no_hp'] ?? '') : ''; ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group full-width">
        <label for="alamat">Alamat</label>
        <textarea name="alamat" id="alamat" rows="4"
            placeholder="Masukkan alamat lengkap"><?php echo isset($data) ? htmlspecialchars($data['alamat'] ?? '') : ''; ?></textarea>
    </div>
</div>
