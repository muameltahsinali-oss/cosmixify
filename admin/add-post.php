<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php"); // إعادة توجيه إلى صفحة تسجيل الدخول إذا لم يكن هناك جلسة
    exit();
    
}
include '../db-conn.php';

?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>رفع صورة جديدة</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            margin-bottom: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            border-color: #7d4bdb; /* اللون البنفسجي */
            box-shadow: 0 0 0 0.2rem rgba(125, 75, 219, 0.25);
        }
        .btn-purple {
            background-color: #7d4bdb;
            color: white;
        }
        .btn-purple:hover {
            background-color: #5c3cad;
            color: white;
        }
        .custom-file-input:lang(ar)~.custom-file-label::after {
            content: "اختيار ملف";
        }
        .custom-file-label::after {
            content: "تصفح";
        }
        .bootstrap-tagsinput {
            width: 100%;
            padding: .5rem;
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
            height: auto;
        }
        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .tag {
            background-color: #7d4bdb;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 5px;
            margin-right: 5px;
        }
        .tag-remove {
            cursor: pointer;
            margin-left: 8px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php' ?>

<div class="container">
    <h2 class="text-center mb-4">رفع صورة جديدة</h2>
    <form action="upload_image.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
            <label for="title">العنوان</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="أدخل عنوان الصورة" required>
        </div>

        <div class="form-group">
            <label for="description">الوصف الانجليزي</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="أدخل وصف للصورة" required></textarea>
        </div>
        <div class="form-group">
            <label for="description">الوصف العربي</label>
            <textarea class="form-control" id="ardescription" name="ardescription" rows="3" placeholder="أدخل وصف للصورة" required></textarea>
        </div>
        <div class="form-group">
            <label for="source">المصدر</label>
            <input type="text" class="form-control" id="source" name="source" placeholder="أدخل اسم المصدر">
        </div>

        <div class="form-group">
            <label for="sourceUrl">رابط المصدر</label>
            <input type="url" class="form-control" id="sourceUrl" name="sourceUrl" placeholder="أدخل رابط المصدر">
        </div>

        <div class="form-group">
            <label for="date">التاريخ</label>
            <input type="month" class="form-control" id="date" name="date">
        </div>

        <div class="form-group">
            <label for="category">الفئة</label>
            <input type="text" class="form-control" id="category" name="category" placeholder="أدخل فئة الصورة" required>
        </div>

        <div class="form-group">
            <label for="image">تحميل الصورة</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="image" name="image" required>
                <label class="custom-file-label" for="image">اختر ملفًا...</label>
            </div>
        </div>
        <div class="form-group">
            <label for="tags">العلامات (Tags)</label>
            <input type="text" class="form-control" id="tagInput" placeholder="أضف علامات واضغط Enter">
            <div class="tags-container" id="tagsContainer"></div>
            <input type="hidden" name="tags" id="hiddenTagsInput">
        </div>
        <button type="submit" name="submit" class="btn btn-purple btn-block mt-4">رفع الصورة</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tagInput = document.getElementById("tagInput");
        const tagsContainer = document.getElementById("tagsContainer");
        const hiddenTagsInput = document.getElementById("hiddenTagsInput");

        let tags = [];

        // إضافة العلامة عند الضغط على مفتاح Enter
        tagInput.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                const tag = tagInput.value.trim();

                if (tag && !tags.includes(tag)) {
                    tags.push(tag);
                    updateTags();
                }

                tagInput.value = "";
            }
        });

        // حذف العلامة عند النقر على "X"
        function removeTag(tag) {
            tags = tags.filter(t => t !== tag);
            updateTags();
        }

        // تحديث العلامات الظاهرة والمدخلة
        function updateTags() {
            tagsContainer.innerHTML = '';
            tags.forEach(tag => {
                const tagElement = document.createElement('span');
                tagElement.className = 'tag';
                tagElement.textContent = tag;

                const removeBtn = document.createElement('span');
                removeBtn.className = 'tag-remove';
                removeBtn.textContent = '×';
                removeBtn.onclick = () => removeTag(tag);

                tagElement.appendChild(removeBtn);
                tagsContainer.appendChild(tagElement);
            });

            hiddenTagsInput.value = tags.join(',');
        }
    });
</script>
<script>
    CKEDITOR.replace('description');
    CKEDITOR.replace('ardescription');
</script>
</body>
</html>