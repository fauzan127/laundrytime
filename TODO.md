# Remove Email Verification

## Information Gathered
- Email verification implemented with link-based and code-based methods.
- Routes: verification.notice, verification.verify, verification.send, verification.code, verification.code.verify, verification.code.resend
- Controllers: EmailVerificationNotificationController, EmailVerificationPromptController, VerifyEmailController, VerifyCodeController
- User model: hasVerifiedEmail, markEmailAsVerified, sendEmailVerificationNotification, sendEmailVerificationNotificationWithCode methods; fillable includes verification_code, verification_code_expires_at; casts includes verification_code_expires_at
- Notifications: VerifyEmail, VerifyEmailWithCode
- Views: verify-email.blade.php, resend in profile form
- Migration: 2025_11_11_155917_add_verification_code_to_users_table.php
- Tests: EmailVerificationTest.php, VerifyCodeTest.php
- RegisteredUserController: triggers Registered event, logs in, redirects to dashboard
- GoogleController: sets email_verified_at for Google users

## Plan
- [ ] Remove verification routes from routes/auth.php
- [ ] Delete verification controllers
- [ ] Update User model: remove verification methods, remove verification_code from fillable, remove verification_code_expires_at from casts
- [ ] Delete notifications
- [ ] Delete verify-email.blade.php view
- [ ] Remove verification resend from profile form
- [ ] Rollback and delete verification migration
- [ ] Update RegisteredUserController: set email_verified_at on create, remove Registered event
- [ ] Delete verification tests
- [ ] Run migrations and tests

## Followup Steps
- [ ] Execute php artisan migrate:rollback --step=1
- [ ] Delete migration file
- [ ] Run php artisan test
- [ ] Test registration and login flow
