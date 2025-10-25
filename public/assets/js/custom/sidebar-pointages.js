// إضافة JavaScript لضمان عمل القوائم المنسدلة في sidebar

$(document).ready(function() {
    // تأكد من عمل collapse للقوائم المنسدلة
    $('[data-toggle="collapse"]').on('click', function(e) {
        e.preventDefault();

        const target = $(this).attr('href');
        const $target = $(target);

        if ($target.length) {
            $target.collapse('toggle');

            // تغيير حالة الأيقونة
            const $arrow = $(this).find('.menu-arrow');
            if ($target.hasClass('show')) {
                $arrow.addClass('rotated');
            } else {
                $arrow.removeClass('rotated');
            }
        }
    });

    // إضافة CSS للأيقونة المدورة
    $('<style>').prop('type', 'text/css').html(`
        .menu-arrow {
            transition: transform 0.3s ease;
        }
        .menu-arrow.rotated {
            transform: rotate(90deg);
        }
        .nav-item .collapse.show {
            display: block !important;
        }
        .nav-item .sub-menu {
            padding-left: 20px;
        }
        .nav-item .sub-menu .nav-link {
            padding: 8px 15px;
            font-size: 13px;
        }
    `).appendTo('head');
});

// التأكد من تحميل Bootstrap collapse إذا لم يكن محملاً
if (typeof $.fn.collapse === 'undefined') {
    console.log('Bootstrap collapse not loaded, using custom implementation');

    $.fn.collapse = function(action) {
        return this.each(function() {
            const $this = $(this);

            if (action === 'toggle') {
                if ($this.hasClass('show')) {
                    $this.removeClass('show').slideUp();
                } else {
                    $this.addClass('show').slideDown();
                }
            }
        });
    };
}
