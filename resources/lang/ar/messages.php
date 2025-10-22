<?php

return [
    // Authentication
    'auth' => [
        'login_success' => 'تم تسجيل الدخول بنجاح',
        'logout_success' => 'تم تسجيل الخروج بنجاح',
        'registration_success' => 'تم التسجيل بنجاح',
        'invalid_credentials' => 'بيانات اعتماد غير صحيحة',
        'email_already_exists' => 'البريد الإلكتروني موجود بالفعل',
        'password_reset_sent' => 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني',
        'password_reset_success' => 'تم إعادة تعيين كلمة المرور بنجاح',
    ],

    // Courses
    'courses' => [
        'created_success' => 'تم إنشاء الدورة بنجاح',
        'updated_success' => 'تم تحديث الدورة بنجاح',
        'deleted_success' => 'تم حذف الدورة بنجاح',
        'not_found' => 'لم يتم العثور على الدورة',
        'enrollment_success' => 'تم التسجيل في الدورة بنجاح',
        'already_enrolled' => 'أنت مسجل بالفعل في هذه الدورة',
        'enrollment_failed' => 'فشل التسجيل',
    ],

    // Payments
    'payments' => [
        'payment_success' => 'تم الدفع بنجاح',
        'payment_failed' => 'فشل الدفع',
        'insufficient_balance' => 'رصيد المحفظة غير كافي',
        'invalid_amount' => 'مبلغ دفع غير صحيح',
        'payment_pending' => 'الدفع قيد الانتظار',
    ],

    // Wallet
    'wallet' => [
        'deposit_success' => 'تم الإيداع بنجاح',
        'withdrawal_success' => 'تم السحب بنجاح',
        'insufficient_funds' => 'أموال غير كافية',
        'transaction_failed' => 'فشلت المعاملة',
    ],

    // Notifications
    'notifications' => [
        'marked_as_read' => 'تم وضع علامة على الإخطار كمقروء',
        'marked_all_as_read' => 'تم وضع علامة على جميع الإخطارات كمقروءة',
        'deleted_success' => 'تم حذف الإخطار بنجاح',
    ],

    // Errors
    'errors' => [
        'unauthorized' => 'وصول غير مصرح به',
        'forbidden' => 'محظور',
        'not_found' => 'لم يتم العثور على المورد',
        'validation_failed' => 'فشل التحقق',
        'server_error' => 'حدث خطأ في الخادم',
        'invalid_request' => 'طلب غير صحيح',
    ],

    // General
    'general' => [
        'success' => 'نجاح',
        'error' => 'خطأ',
        'warning' => 'تحذير',
        'info' => 'معلومات',
        'loading' => 'جاري التحميل...',
        'please_wait' => 'يرجى الانتظار...',
    ],
];

