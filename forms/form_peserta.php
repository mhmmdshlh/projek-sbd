<div class="form-row">
    <div class="form-group">
        <label for="id_pelatihan">Pelatihan <span class="required">*</span></label>
        <select name="id_pelatihan" id="id_pelatihan" required>
            <option value="">Pilih Pelatihan</option>
            <?php foreach ($data_pelatihan as $pelatihan): ?>
                <option value="<?php echo $pelatihan['id_pelatihan']; ?>" <?php echo (isset($data) && $pelatihan['id_pelatihan'] == $data['id_pelatihan']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($pelatihan['judul_pelatihan']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="id_anggota">Nama Anggota <span class="required">*</span></label>
        <select name="id_anggota" id="id_anggota" required>
            <option value="">Pilih Anggota</option>
            <?php foreach ($data_anggota as $anggota): ?>
                <option value="<?php echo $anggota['id_anggota']; ?>" <?php echo (isset($data) && $anggota['id_anggota'] == $data['id_anggota']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($anggota['nama']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="status_kehadiran">Status Kehadiran <span class="required">*</span></label>
        <select name="status_kehadiran" id="status_kehadiran" required>
            <option value="">Pilih Status</option>
            <option value="Hadir" <?php echo (isset($data) && $data['status_kehadiran'] == 'Hadir') ? 'selected' : ''; ?>>
                Hadir</option>
            <option value="Tidak Hadir" <?php echo (isset($data) && $data['status_kehadiran'] == 'Tidak Hadir') ? 'selected' : ''; ?>>Tidak Hadir</option>
            <option value="Izin" <?php echo (isset($data) && $data['status_kehadiran'] == 'Izin') ? 'selected' : ''; ?>>
                Izin</option>
        </select>
    </div>

    <div class="form-group">
        <!-- Empty space for alignment -->
    </div>
</div>
</div>
