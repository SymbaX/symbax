function openEditModal(userId, collegeId, departmentId, roleId) {
    const collegeSelect = document.getElementById("editUserCollege");
    const departmentSelect = document.getElementById("editUserDepartment");
    const roleSelect = document.getElementById("editUserRole");
    let currentUserID = userId;

    const form = document.getElementById("editUserForm");
    form.action = form.action.replaceAll("USER_ID", currentUserID);

    // 選択されたユーザーのCollege IDを設定する
    for (let i = 0; i < collegeSelect.options.length; i++) {
        if (collegeSelect.options[i].value === collegeId) {
            collegeSelect.options[i].selected = true;
            break;
        }
    }

    // 選択されたユーザーのCollege IDを設定する
    for (let i = 0; i < departmentSelect.options.length; i++) {
        if (departmentSelect.options[i].value === departmentId) {
            departmentSelect.options[i].selected = true;
            break;
        }
    }
    // カレッジの選択肢が変更された時の処理
    collegeSelect.addEventListener("change", function () {
        changeDepartmentOptions();
    });

    // カレッジの初期選択に応じて学科の表示/非表示と選択状態を設定する
    changeDepartmentOptions();

    // 選択されたユーザーのロールを設定する
    for (let i = 0; i < roleSelect.options.length; i++) {
        if (roleSelect.options[i].value === roleId) {
            roleSelect.options[i].selected = true;
            break;
        }
    }

    // モーダルウィンドウを表示する
    const modal = document.getElementById("editUserModal");
    modal.classList.remove("hidden");

    // カレッジに応じた学科の表示/非表示と選択状態を制御する関数
    function changeDepartmentOptions() {
        const selectedCollegeId = collegeSelect.value;

        for (let i = 0; i < departmentSelect.options.length; i++) {
            const departmentOption = departmentSelect.options[i];
            if (departmentOption.dataset.collegeId === selectedCollegeId) {
                departmentOption.style.display = "";
            } else {
                departmentOption.style.display = "none";
            }
        }

        // 選択されたカレッジ内の学科を強制的に選択する
        const selectedDepartment = departmentSelect.querySelector(
            `option[data-college-id="${selectedCollegeId}"][value="${departmentId}"]`
        );
        if (selectedDepartment) {
            selectedDepartment.selected = true;
        } else {
            // 学科が選択されていない場合は、最初のカレッジに関連する学科を選択する
            const defaultDepartment = departmentSelect.querySelector(
                `option[data-college-id="${selectedCollegeId}"]`
            );
            if (defaultDepartment) {
                defaultDepartment.selected = true;
            }
        }
    }
}

function closeEditModal() {
    const modal = document.getElementById("editUserModal");
    modal.classList.add("hidden");
}
