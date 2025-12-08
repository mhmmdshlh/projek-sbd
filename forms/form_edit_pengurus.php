<div class="form-row">
    <div class="form-group">
        <label for="id_anggota">Nama Anggota <span class="required">*</span></label>
        <select name="id_anggota" id="id_anggota" required>
            <option value="">Pilih Anggota</option>
            <?php foreach ($data_anggota as $anggota): ?>
            <option value="<?php echo $anggota['id_anggota']; ?>" 
                    <?php echo $anggota['id_anggota'] == $data['id_anggota'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($anggota['nama']); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="jabatan">Jabatan <span class="required">*</span></label>
        <input type="text" name="jabatan" id="jabatan" required placeholder="Contoh: Ketua" 
               value="<?php echo htmlspecialchars($data['jabatan']); ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="periode">Periode <span class="required">*</span></label>
        <input type="text" name="periode" id="periode" required placeholder="Contoh: 2025-2028" 
               value="<?php echo htmlspecialchars($data['periode']); ?>">
    </div>

    <div class="form-group">
        <!-- Empty space for alignment -->
    </div>
</div>
