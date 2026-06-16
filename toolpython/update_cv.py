import os

file_path = "c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/php_views/Views/tutor/dashboard.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# The UI we want to insert instead of the single "Chuyên môn" text input:
new_ui = """
                                <!-- CV Builder Section -->
                                <div class="mb-4 p-4 border rounded bg-light">
                                    <h5 class="fw-bold mb-3"><i class="bi bi-list-check text-primary"></i> Lĩnh vực giảng dạy (Goals)</h5>
                                    <div class="row g-2 mb-4">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="luyen thi"> Ôn thi HSK</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="giao tiep"> Giao tiếp</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="thuong mai"> Thương mại</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="tre em"> Trẻ em</label>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="bi bi-star text-warning"></i> Kỹ năng chuyên sâu (Skills)</h5>
                                    <div class="row g-2 mb-4">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="nghe noi"> Nghe - Nói</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="phat am"> Phát âm chuẩn</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="doc viet"> Đọc - Viết</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="ngu phap"> Ngữ pháp</label>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart text-success"></i> Trình độ nhận dạy (Levels)</h5>
                                    <div class="row g-2 mb-4">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_levels" value="co ban"> HSK 1-2 (Cơ bản)</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_levels" value="trung cap"> HSK 3-4 (Trung cấp)</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_levels" value="nang cao"> HSK 5-6 (Nâng cao)</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_levels" value="sieu cap"> HSK 7-9 (Master)</label>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="bi bi-laptop text-info"></i> Hình thức dạy (Modes)</h5>
                                    <div class="row g-2 mb-4">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="online"> Học Online (Meet/Zoom)</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="offline"> Học Offline (Trực tiếp)</label>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="bi bi-people text-danger"></i> Đối tượng học viên (Ages)</h5>
                                    <div class="row g-2 mb-4">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="tre em"> Trẻ em</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="sinh vien"> Học sinh/Sinh viên</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="nguoi di lam"> Người đi làm</label>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="bi bi-clock text-secondary"></i> Khung giờ rảnh (Schedule)</h5>
                                    <div class="row g-2 mb-4">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="weekdays"> Sáng/Chiều (T2-T6)</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="evenings"> Buổi tối (T2-T6)</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="weekends"> Cuối tuần (T7-CN)</label>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="bi bi-heart text-danger"></i> Phong cách dạy (Style)</h5>
                                    <div class="row g-2 mb-2">
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="vui ve"> Vui vẻ/Hài hước</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="nghiem khac"> Nghiêm khắc/Kỷ luật</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="kien nhan"> Nhẹ nhàng/Kiên nhẫn</label>
                                        <label class="col-6 col-md-3"><input type="checkbox" name="tutor_tags" value="truyen cam hung"> Truyền cảm hứng</label>
                                    </div>
                                </div>
"""

old_ui = """                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Chuyên môn</label>
                                        <input type="text" class="form-control bg-light" id="tutorSpec" required placeholder="Giao tiếp, Ôn thi HSK...">
                                    </div>"""

new_content = content.replace(old_ui, new_ui + """                                <div class="row">""")
with open(file_path, "w", encoding="utf-8") as f:
    f.write(new_content)


# Now update Javascript at bottom of dashboard.php
# We need to pre-fill the checkboxes based on existing tags and levels

js_old = """                document.getElementById('tutorBio').value = profile.bio || '';
                document.getElementById('tutorSpec').value = profile.specialization || '';
                document.getElementById('tutorRate').value = profile.hourlyRate || '';"""

js_new = """                document.getElementById('tutorBio').value = profile.bio || '';
                document.getElementById('tutorRate').value = profile.hourlyRate || '';
                
                // Prefill checkboxes
                const currentTags = (profile.tagsVector || '').split(',').map(s => s.trim());
                document.querySelectorAll('input[name="tutor_tags"]').forEach(cb => {
                    if (currentTags.includes(cb.value)) cb.checked = true;
                });
                
                const currentLevels = (profile.teachingLevels || '').split(',').map(s => s.trim());
                document.querySelectorAll('input[name="tutor_levels"]').forEach(cb => {
                    if (currentLevels.includes(cb.value)) cb.checked = true;
                });"""

with open(file_path, "r", encoding="utf-8") as f:
    content2 = f.read()
content2 = content2.replace(js_old, js_new)

js_submit_old = """            const bio = document.getElementById('tutorBio').value;
            const spec = document.getElementById('tutorSpec').value;
            const rate = parseInt(document.getElementById('tutorRate').value);
            const avatarFile = document.getElementById('tutorAvatar').files[0];
            const certFile = document.getElementById('tutorCert').files[0];

            try {
                let avatarBase64 = "", certBase64 = "";
                if (avatarFile) avatarBase64 = await toBase64(avatarFile);
                if (certFile) certBase64 = await toBase64(certFile);

                await TutorAPI.updateMyProfile({ bio, specialization: spec, hourlyRate: rate, avatarBase64, certificateBase64 });"""

js_submit_new = """            const bio = document.getElementById('tutorBio').value;
            const rate = parseInt(document.getElementById('tutorRate').value);
            const avatarFile = document.getElementById('tutorAvatar').files[0];
            const certFile = document.getElementById('tutorCert').files[0];
            
            // Gather tags
            const tags = Array.from(document.querySelectorAll('input[name="tutor_tags"]:checked')).map(cb => cb.value);
            const tagsVector = tags.join(', ');
            
            // Gather levels
            const levels = Array.from(document.querySelectorAll('input[name="tutor_levels"]:checked')).map(cb => cb.value);
            const teachingLevels = levels.join(', ');
            
            // Specialization is just a quick summary
            const specialization = tags.slice(0, 3).join(', ') || 'Chưa cập nhật';

            try {
                let avatarBase64 = "", certBase64 = "";
                if (avatarFile) avatarBase64 = await toBase64(avatarFile);
                if (certFile) certBase64 = await toBase64(certFile);

                await TutorAPI.updateMyProfile({ bio, specialization, tagsVector, teachingLevels, hourlyRate: rate, avatarBase64, certificateBase64 });"""

content2 = content2.replace(js_submit_old, js_submit_new)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content2)


print("UI Updated!")
