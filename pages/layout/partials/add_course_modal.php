<div class="modal fade" id="courseModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="courseModalTitle" class="modal-title">Add Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST">
                <input type="hidden" id="courseModalAction" name="action" value="add_course">
                <input type="hidden" id="courseModalOldCourseId" name="old_course_id" value="">

                <div class="modal-body">
                    <select id="courseSelect" name="course_id" class="form-control mb-2" required>
                        <option value="">Select Course</option>
                        <?php foreach ($allCourses as $course): ?>
                            <option value="<?= (int)($course['id'] ?? 0) ?>">
                                <?= htmlspecialchars($course['course_name'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="modal-footer">
                    <button id="courseModalSubmit" class="btn pink-btn" type="submit">Add</button>
                </div>
            </form>

        </div>
    </div>
</div>
