function openEditModal(
    userId,
    userName,
    userEmail,
    collegeId,
    departmentId,
    roleId
) {
    const $name = $("#editUserName");
    const $email = $("#editUserEmail");
    const $collegeSelect = $("#editUserCollege");
    const $departmentSelect = $("#editUserDepartment");
    const $roleSelect = $("#editUserRole");
    const currentUserID = userId;

    const $form = $("#editUserForm");
    $form.attr(
        "action",
        $form.attr("action").replaceAll("USER_ID", currentUserID)
    );

    // 元の値を代入する
    $name.val(userName);
    $email.val(userEmail);

    // 選択されたユーザーのCollege IDを設定する
    $collegeSelect.val(collegeId);

    // カレッジの選択肢が変更された時の処理
    $collegeSelect.on("change", function () {
        changeDepartmentOptions();
    });

    // カレッジの初期選択に応じて学科の表示/非表示と選択状態を設定する
    changeDepartmentOptions();

    // 選択されたユーザーのロールを設定する
    $roleSelect.val(roleId);

    // モーダルウィンドウを表示する
    const $modal = $("#editUserModal");
    $modal.removeClass("hidden");

    // カレッジに応じた学科の表示/非表示と選択状態を制御する関数
    function changeDepartmentOptions() {
        const selectedCollegeId = $collegeSelect.val();

        $departmentSelect.find("option").each(function () {
            const $departmentOption = $(this);
            if ($departmentOption.data("college-id") === selectedCollegeId) {
                $departmentOption.css("display", "");
                $departmentOption.prop("disabled", false);
            } else {
                $departmentOption.css("display", "none");
                $departmentOption.prop("disabled", true);
            }
        });

        // 選択されたカレッジ内の学科を強制的に選択する
        const $selectedDepartment = $departmentSelect.find(
            `option[data-college-id="${selectedCollegeId}"][value="${departmentId}"]`
        );
        if ($selectedDepartment.length) {
            $selectedDepartment.prop("selected", true);
        } else {
            // 学科が選択されていない場合は、最初のカレッジに関連する学科を選択する
            const $defaultDepartment = $departmentSelect
                .find(`option[data-college-id="${selectedCollegeId}"]`)
                .first();
            if ($defaultDepartment.length) {
                $defaultDepartment.prop("selected", true);
            }
        }
    }
}
