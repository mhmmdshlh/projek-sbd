<div class="form-row">
    <div class="form-group">
        <label for="nama">Nama Lengkap <span class="required">*</span></label>
        <input type="text" name="nama" id="nama" required placeholder="Masukkan nama lengkap" <?php
        if (strpos($_SERVER['REQUEST_URI'], 'edit_data') !== false) {
            echo 'value="' . htmlspecialchars($data['nama'] ?? '') . '"';
        }
    ?>>
    </div>

    <div class="form-group">
        <label for="no_hp">No HP</label>
        <input type="text" name="no_hp" id="no_hp" placeholder="Contoh: 081234567890" <?php
        if (strpos($_SERVER['REQUEST_URI'], 'edit_data') !== false) {
            echo 'value="' . htmlspecialchars($data['no_hp'] ?? '') . '"';
        }
    ?>>
    </div>
</div>

<div class="form-row">
    <div class="form-group" style="grid-column: 1 / -1;">
        <label for="alamat">Alamat</label>
        <textarea name="alamat" id="alamat" placeholder="Masukkan alamat lengkap"><?php
        if (strpos($_SERVER['REQUEST_URI'], 'edit_data') !== false) {
            echo htmlspecialchars($data['alamat'] ?? '');
        }
    ?></textarea>
    </div>
</div>
