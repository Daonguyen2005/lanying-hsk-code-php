import os

file_path = "c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/php_views/Views/layouts/footer.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

mapping_js = """        const tagLabels = {
            "luyen thi": "Ôn thi HSK", "giao tiep": "Giao tiếp", "thuong mai": "Thương mại", "tre em": "Trẻ em",
            "nghe noi": "Nghe - Nói", "phat am": "Phát âm chuẩn", "doc viet": "Đọc - Viết", "ngu phap": "Ngữ pháp",
            "online": "Học Online", "offline": "Học Offline", 
            "sinh vien": "Học sinh/Sinh viên", "nguoi di lam": "Người đi làm",
            "weekdays": "Giờ hành chính", "evenings": "Buổi tối", "weekends": "Cuối tuần",
            "vui ve": "Vui vẻ", "nghiem khac": "Nghiêm khắc", "kien nhan": "Kiên nhẫn", "truyen cam hung": "Truyền cảm hứng",
            "co ban": "HSK 1-2", "trung cap": "HSK 3-4", "nang cao": "HSK 5-6", "sieu cap": "HSK 7-9"
        };
        
        function formatTags(tagsStr) {
            if (!tagsStr) return [];
            return tagsStr.split(',').map(t => t.trim()).filter(t => t).map(t => tagLabels[t] || t);
        }
        
        window.renderTutorsPage = function() {"""

content = content.replace("        window.renderTutorsPage = function() {", mapping_js)

old_tutor_loop = """            pageData.forEach((tutor, index) => {
                const delayClass = `delay-${(index % 3 + 1) * 100}`;
                const levels = tutor.teachingLevels ? tutor.teachingLevels.toUpperCase() : 'HSK 5-6';
                const rate = tutor.hourlyRate ? tutor.hourlyRate.toLocaleString('vi-VN') + 'đ/buổi' : 'Thỏa thuận';
                const tagsArr = tutor.tagsVector ? tutor.tagsVector.split(',').map(t => t.trim()) : [];
                
                const avatarHtml = tutor.avatarUrl && tutor.avatarUrl.length > 5 
                    ? `<img src="${tutor.avatarUrl}" style="width:72px;height:72px;object-fit:cover;border-radius:50%;box-shadow:0 4px 12px rgba(0,0,0,0.12)" alt="${tutor.name}">` 
                    : `<div class="tutor-avatar d-flex align-items-center justify-content-center rounded-circle fw-bold" style="width:72px;height:72px;font-size:1.8rem;background:linear-gradient(135deg,#3b82f6,#0ea5e9);color:white;box-shadow:0 4px 12px rgba(59,130,246,0.35);">${tutor.name.charAt(0).toUpperCase()}</div>`;
                
                const approvedTick = tutor.isApproved ? `<span class="badge bg-success bg-opacity-10 text-success rounded-pill ms-1" title="Đã xét duyệt chứng chỉ HSK"><i class="bi bi-patch-check-fill"></i> HSK Uy tín</span>` : '';
                const tagBadges = tagsArr.slice(0, 3).map(tag => `<span class="badge bg-light text-dark border px-2 py-1 small">${tag}</span>`).join('');

                const card = document.createElement('div');
                card.className = `col-md-6 col-lg-4 animate-fade-up ${delayClass} tutor-card-col`;
                card.dataset.tags = tutor.tagsVector || '';
                card.innerHTML = `
                    <div class="glass-panel h-100 p-4">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            ${avatarHtml}
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">${tutor.name}</h5>
                                <div>${approvedTick}</div>
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill mt-1">${tutor.specialization || 'Gia sư Tiếng Trung'}</span>
                            </div>
                        </div>
                        <p class="text-secondary mb-3 line-clamp-3" style="min-height: 66px; font-size: 0.9rem;">
                            ${tutor.bio || 'Gia sư tận tâm, chuyên nghiệp. Cam kết chất lượng dạy học.'}
                        </p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-award text-warning"></i> ${levels}</span>
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-currency-dollar text-success"></i> ${rate}</span>
                        </div>
                        <div class="d-flex flex-wrap gap-1 mb-4">${tagBadges}</div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary-custom flex-grow-1 py-2 rounded-pill" onclick="bookTutor(${tutor.id})">
                                <i class="bi bi-calendar2-check me-1"></i>Đặt lịch học
                            </button>
                            <button class="btn btn-outline-secondary rounded-pill py-2 px-3" title="Xem hồ sơ" onclick="viewTutorProfile(${tutor.id}, '${tutor.name}')">
                                <i class="bi bi-person"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });"""

new_tutor_loop = """            pageData.forEach((tutor, index) => {
                const delayClass = `delay-${(index % 3 + 1) * 100}`;
                
                const levelLabels = formatTags(tutor.teachingLevels);
                const levels = levelLabels.length > 0 ? levelLabels.join(', ') : 'HSK 1-6';
                
                const rate = tutor.hourlyRate ? tutor.hourlyRate.toLocaleString('vi-VN') + 'đ/giờ' : 'Thỏa thuận';
                const tagsArr = formatTags(tutor.tagsVector);
                
                const avatarHtml = tutor.avatarUrl && tutor.avatarUrl.length > 5 
                    ? `<img src="${tutor.avatarUrl}" style="width:72px;height:72px;object-fit:cover;border-radius:50%;box-shadow:0 4px 12px rgba(0,0,0,0.12)" alt="${tutor.name}">` 
                    : `<div class="tutor-avatar d-flex align-items-center justify-content-center rounded-circle fw-bold" style="width:72px;height:72px;font-size:1.8rem;background:linear-gradient(135deg,#3b82f6,#0ea5e9);color:white;box-shadow:0 4px 12px rgba(59,130,246,0.35);">${tutor.name.charAt(0).toUpperCase()}</div>`;
                
                const approvedTick = tutor.isApproved ? `<span class="badge bg-success bg-opacity-10 text-success rounded-pill ms-1" title="Đã xét duyệt chứng chỉ HSK"><i class="bi bi-patch-check-fill"></i> HSK Uy tín</span>` : '';
                
                const tagBadges = tagsArr.slice(0, 4).map(tag => `<span class="badge bg-info bg-opacity-10 text-dark border border-info px-2 py-1 small">${tag}</span>`).join('');
                
                const specLabels = formatTags(tutor.specialization);
                const specStr = specLabels.length > 0 ? specLabels.join(' & ') : 'Gia sư Tiếng Trung';

                const card = document.createElement('div');
                card.className = `col-md-6 col-lg-4 animate-fade-up ${delayClass} tutor-card-col`;
                card.dataset.tags = tutor.tagsVector || '';
                card.innerHTML = `
                    <div class="glass-panel h-100 p-4">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            ${avatarHtml}
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">${tutor.name}</h5>
                                <div>${approvedTick}</div>
                                <div class="mt-2"><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2"><i class="bi bi-stars"></i> ${specStr}</span></div>
                            </div>
                        </div>
                        <p class="text-secondary mb-3 line-clamp-3" style="min-height: 66px; font-size: 0.9rem;">
                            ${tutor.bio || 'Gia sư tận tâm, chuyên nghiệp. Cam kết chất lượng dạy học và giúp học viên đạt mục tiêu mong muốn.'}
                        </p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-award text-warning"></i> Dạy: ${levels}</span>
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-currency-dollar text-success"></i> ${rate}</span>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-4">${tagBadges}</div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary-custom flex-grow-1 py-2 rounded-pill" onclick="bookTutor(${tutor.id})">
                                <i class="bi bi-calendar2-check me-1"></i> Đặt lịch học
                            </button>
                            <button class="btn btn-outline-secondary rounded-pill py-2 px-3" title="Xem hồ sơ" onclick="viewTutorProfile(${tutor.id}, '${tutor.name}')">
                                <i class="bi bi-person"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });"""

content = content.replace(old_tutor_loop, new_tutor_loop)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Updated footer.php format!")
