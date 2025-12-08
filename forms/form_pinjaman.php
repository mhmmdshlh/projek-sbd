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
        <label for="jumlah">Jumlah Pinjaman <span class="required">*</span></label>
        <input type="number" name="jumlah" id="jumlah" required placeholder="Contoh: 5000000" step="0.01" min="0">
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="tgl_pinjam">Tanggal Pinjam <span class="required">*</span></label>
        <input type="date" name="tgl_pinjam" id="tgl_pinjam" required>
    </div>

    <div class="form-group">
        <label for="tgl_jatuhtempo">Tanggal Jatuh Tempo <span class="required">*</span></label>
        <input type="date" name="tgl_jatuhtempo" id="tgl_jatuhtempo" required>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="status_pinjam">Status Pinjaman <span class="required">*</span></label>
        <select name="status_pinjam" id="status_pinjam" required>
            <option value="" hidden selected disabled>Pilih Status</option>
            <option value="Aktif">Aktif</option>
            <option value="Lunas">Lunas</option>
            <option value="Menunggak">Menunggak</option>
        </select>
    </div>
</div>
