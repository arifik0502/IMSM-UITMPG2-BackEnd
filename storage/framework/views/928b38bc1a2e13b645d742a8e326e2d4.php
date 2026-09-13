<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'Attendance System')); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center gap-3 mb-10">
            <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'w-10 h-10 text-brand-600']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-10 h-10 text-brand-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
            <span class="text-2xl font-bold text-gray-800"><?php echo e(config('app.name')); ?></span>
        </div>

        <?php if(session('success')): ?>
            <div class="mb-8 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome</h1>
        <p class="text-gray-600 mb-10">Employee attendance, leave, and equipment borrowing in one place.</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="card flex flex-col">
                <h2 class="font-semibold text-gray-800 mb-2">Employee</h2>
                <p class="text-sm text-gray-500 mb-6 flex-1">Clock in/out, track attendance, chat with colleagues, and manage your own leave and equipment bookings.</p>
                <a href="<?php echo e(route('login')); ?>" class="btn-primary justify-center">Employee Login</a>
                <a href="<?php echo e(route('register')); ?>" class="text-sm text-center text-brand-600 hover:underline mt-3">Create an account</a>
            </div>

            <div class="card flex flex-col">
                <h2 class="font-semibold text-gray-800 mb-2">Guest &mdash; Apply Leave</h2>
                <p class="text-sm text-gray-500 mb-6 flex-1">Not an employee account holder? Submit a leave application with just your name and email &mdash; no login needed.</p>
                <a href="<?php echo e(route('guest.leave.create')); ?>" class="btn-secondary justify-center">Apply for Leave</a>
            </div>

            <div class="card flex flex-col">
                <h2 class="font-semibold text-gray-800 mb-2">Borrow Equipment</h2>
                <p class="text-sm text-gray-500 mb-6 flex-1">Book a laptop, webcam, projector, or PA system. Open to both employees and guests.</p>
                <a href="<?php echo e(route('borrow.create')); ?>" class="btn-secondary justify-center">Borrow Equipment</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\ISMS-Project\backend\resources\views/welcome.blade.php ENDPATH**/ ?>