<div class="form-row">
    <div class="form-group">
        <label for="judul">Judul Pelatihan <span class="required">*</span></label>
        <input type="text" name="judul" id="judul" required placeholder="Contoh: Pelatihan Koperasi Modern"
            value="<?php echo isset($data) ? htmlspecialchars($data['judul_pelatihan']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="tgl_pelatihan">Tanggal Pelatihan <span class="required">*</span></label>
        <input type="date" name="tgl_pelatihan" id="tgl_pelatihan" required
            value="<?php echo isset($data) ? htmlspecialchars($data['tanggal']) : ''; ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="lokasi">Lokasi</label>
        <input type="text" name="lokasi" id="lokasi" placeholder="Contoh: Gedung Serbaguna"
            value="<?php echo isset($data) ? htmlspecialchars($data['tempat'] ?? '') : ''; ?>">
    </div>

    <div class="form-group">
        <label for="id_narasumber">Narasumber</label>
        <select name="id_narasumber" id="id_narasumber">
            <option value="">Pilih Narasumber</option>
            <?php foreach ($data_narasumber as $narasumber): ?>
                <option value="<?php echo $narasumber['id_narasumber']; ?>" <?php echo (isset($data) && $narasumber['id_narasumber'] == $data['id_narasumber']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($narasumber['nama']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
