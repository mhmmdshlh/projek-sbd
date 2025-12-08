<div class="form-row">
    <div class="form-group">
        <label for="id_anggota">Nama Anggota <span class="required">*</span></label>
        <select name="id_anggota" id="id_anggota" required>
            <option value="">Pilih Anggota</option>
            <?php foreach ($data_anggota as $anggota): ?>
            <option value="<?php echo $anggota['id_anggota']; ?>">
                <?php echo htmlspecialchars($anggota['nama']); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="jenis_simpanan">Jenis Simpanan <span class="required">*</span></label>
        <select name="jenis_simpanan" id="jenis_simpanan" required>
            <option value="">Pilih Jenis</option>
            <option value="Pokok">Simpanan Pokok</option>
            <option value="Wajib">Simpanan Wajib</option>
            <option value="Sukarela">Simpanan Sukarela</option>
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="jumlah">Jumlah <span class="required">*</span></label>
        <input type="number" name="jumlah" id="jumlah" required placeholder="Contoh: 100000" step="0.01" min="0">
    </div>

    <div class="form-group">
        <label for="tgl_simpan">Tanggal Simpan <span class="required">*</span></label>
        <input type="date" name="tgl_simpan" id="tgl_simpan" required>
    </div>
</div>
