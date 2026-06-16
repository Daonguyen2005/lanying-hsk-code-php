import os

file_path = "c:/Users/VanDao/.gemini/antigravity/scratch/tiengtrungcautrucmvc/php_views/Views/student/dashboard.php"
with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

old_render = """    function renderAIRecommendations(recs) {
        const body = document.getElementById('ai-recs-body');
        if (!recs || recs.length === 0) return;

        body.innerHTML = `<div class="row g-3">` + recs.slice(0, 3).map((t, i) => {
            const score = t.similarity_score || 0;
            const scoreColor = score >= 70 ? 'success' : score >= 40 ? 'warning' : 'secondary';
            const avatarUrl = t.avatarUrl || t.AvatarUrl;
            const name = t.name || t.Name || 'Gia sư';
            const spec = t.specialization || t.Specialization || 'Tiếng Trung';
            
            const avatarHtml = avatarUrl && avatarUrl.length > 5
                ? `<img src="${avatarUrl}" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">`
                : `<div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width:48px;height:48px;background:linear-gradient(135deg,#3b82f6,#0ea5e9);">${name.charAt(0)}</div>`;

            return `
            <div class="col-12">
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 border bg-white">
                    <div class="position-relative">
                        ${avatarHtml}
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-${scoreColor}" style="font-size:0.65rem;">${score}%</span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold small">#${i+1} ${name}</div>
                        <div class="text-secondary" style="font-size:0.8rem;">${spec}</div>
                        <div class="progress mt-1" style="height:4px;">
                            <div class="progress-bar bg-${scoreColor}" style="width:${score}%"></div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-${scoreColor} small">${score}%</div>
                        <div class="text-secondary" style="font-size:0.7rem;">Phù hợp</div>
                    </div>
                </div>
            </div>`;
        }).join('') + `</div>
        <div class="text-center mt-3">
            <a href="/#giasu" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                Xem tất cả gia sư →
            </a>
        </div>`;
    }"""

new_render = """    function renderAIRecommendations(recs) {
        const body = document.getElementById('ai-recs-body');
        if (!recs || recs.length === 0) return;

        body.innerHTML = `<div class="row g-3">` + recs.slice(0, 3).map((t, i) => {
            const score = t.similarity_score || 0;
            const scoreColor = score >= 70 ? 'success' : score >= 40 ? 'warning' : 'secondary';
            const avatarUrl = t.avatarUrl || t.AvatarUrl;
            const name = t.name || t.Name || 'Gia sư';
            
            // Format tags for specialization
            const rawSpec = t.specialization || t.Specialization || '';
            const formattedSpecLabels = typeof formatTags !== 'undefined' ? formatTags(rawSpec) : rawSpec.split(',');
            const spec = formattedSpecLabels.length > 0 ? formattedSpecLabels.join(' & ') : 'Gia sư Tiếng Trung';
            
            const tutorId = t.id || t.Id;
            
            const avatarHtml = avatarUrl && avatarUrl.length > 5
                ? `<img src="${avatarUrl}" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">`
                : `<div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width:48px;height:48px;background:linear-gradient(135deg,#3b82f6,#0ea5e9);">${name.charAt(0).toUpperCase()}</div>`;

            return `
            <div class="col-12">
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 border bg-white shadow-sm hover-elevate transition-all">
                    <div class="position-relative">
                        ${avatarHtml}
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-${scoreColor}" style="font-size:0.65rem;">${score}%</span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold mb-1" style="font-size: 0.95rem;">#${i+1} ${name}</div>
                        <div class="badge bg-primary bg-opacity-10 text-primary mb-2" style="font-size:0.75rem; white-space: normal; text-align: left;"><i class="bi bi-stars"></i> ${spec}</div>
                        <div class="progress" style="height:5px; border-radius: 5px;">
                            <div class="progress-bar bg-${scoreColor}" style="width:${score}%; border-radius: 5px;"></div>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end justify-content-between h-100 ps-2" style="min-width: 80px;">
                        <div class="text-end mb-2">
                            <div class="fw-bold text-${scoreColor}">${score}%</div>
                            <div class="text-secondary" style="font-size:0.65rem;">Độ phù hợp</div>
                        </div>
                        <button class="btn btn-sm btn-primary-custom rounded-pill py-1 px-3 w-100 shadow-sm" style="font-size: 0.75rem;" onclick="if(typeof bookTutor === 'function') bookTutor(${tutorId}); else alert('Vui lòng quay lại trang chủ để đặt lịch!')"><i class="bi bi-calendar-check"></i> Đặt ngay</button>
                    </div>
                </div>
            </div>`;
        }).join('') + `</div>
        <div class="text-center mt-3">
            <a href="/#giasu" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                Xem tất cả gia sư <i class="bi bi-arrow-right"></i>
            </a>
        </div>`;
    }"""

content = content.replace(old_render, new_render)
with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Updated dashboard.php")
