import React, { useState, useEffect, useRef } from 'react';

const AuthModal = () => {
    const [isOpen, setIsOpen] = useState(false);
    const [step, setStep] = useState('method'); // 'method', 'phone', 'otp'
    const [phone, setPhone] = useState('');
    const [phoneError, setPhoneError] = useState('');
    const [otp, setOtp] = useState(['', '', '', '', '', '']);
    const [otpError, setOtpError] = useState('');
    const [isSendingOtp, setIsSendingOtp] = useState(false);
    const [isVerifyingOtp, setIsVerifyingOtp] = useState(false);
    const [countdown, setCountdown] = useState(300);
    const timerRef = useRef(null);
    const otpInputsRef = useRef([]);

    // Listen to open modal events
    useEffect(() => {
        const handleShowModal = () => {
            setIsOpen(true);
            setStep('method');
            setPhone('');
            setPhoneError('');
            setOtp(['', '', '', '', '', '']);
            setOtpError('');
        };

        window.addEventListener('show-login-modal', handleShowModal);

        // Global delegate listener for trigger class elements (e.g. from static templates)
        const handleGlobalClick = (e) => {
            if (e.target.classList.contains('trigger') || e.target.closest('.trigger') || e.target.classList.contains('btn-login') || e.target.closest('.btn-login')) {
                e.preventDefault();
                handleShowModal();
            }
        };
        document.addEventListener('click', handleGlobalClick);

        return () => {
            window.removeEventListener('show-login-modal', handleShowModal);
            document.removeEventListener('click', handleGlobalClick);
            if (timerRef.current) clearInterval(timerRef.current);
        };
    }, []);

    // OTP Countdown Timer
    useEffect(() => {
        if (step === 'otp' && countdown > 0) {
            timerRef.current = setInterval(() => {
                setCountdown((prev) => {
                    if (prev <= 1) {
                        clearInterval(timerRef.current);
                        return 0;
                    }
                    return prev - 1;
                });
            }, 1000);
        } else {
            if (timerRef.current) clearInterval(timerRef.current);
        }

        return () => {
            if (timerRef.current) clearInterval(timerRef.current);
        };
    }, [step, countdown]);

    const formatTime = (seconds) => {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    };

    const validatePhone = (num) => {
        const cleanNum = num.replace(/[. ]/g, '');
        const pattern = /^(0|\+84)([3|5|7|8|9])([0-9]{8})$/;
        return pattern.test(cleanNum);
    };

    const handlePhoneChange = (e) => {
        const val = e.target.value;
        setPhone(val);
        if (val.length >= 10) {
            if (!validatePhone(val)) {
                setPhoneError('Số điện thoại không hợp lệ. Vui lòng kiểm tra lại.');
            } else {
                setPhoneError('');
            }
        } else {
            setPhoneError('');
        }
    };

    const handleSendOtp = async (e) => {
        if (e) e.preventDefault();
        if (!validatePhone(phone)) {
            setPhoneError('Vui lòng nhập đúng định dạng số điện thoại Việt Nam.');
            return;
        }

        setIsSendingOtp(true);
        setPhoneError('');

        try {
            const formData = new URLSearchParams();
            formData.append('action', 'customer_send_otp');
            formData.append('phone', phone);
            formData.append('nonce', window.generic_data.nonces?.send_otp || '');

            const response = await fetch(window.generic_data.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData.toString()
            });

            const data = await response.json();
            if (data.success) {
                // Toast notification
                if (window.toastr) {
                    window.toastr.success('Gửi mã OTP thành công!', 'Thành công');
                }
                setStep('otp');
                setCountdown(300);
                setOtp(['', '', '', '', '', '']);
                setOtpError('');
                // Focus first input next tick
                setTimeout(() => {
                    if (otpInputsRef.current[0]) otpInputsRef.current[0].focus();
                }, 100);
            } else {
                setPhoneError(data.data?.message || 'Không thể gửi OTP. Vui lòng thử lại.');
            }
        } catch (err) {
            setPhoneError('Lỗi hệ thống. Vui lòng thử lại sau.');
            console.error('Send OTP Error:', err);
        } finally {
            setIsSendingOtp(false);
        }
    };

    const handleVerifyOtp = async (otpValue) => {
        setIsVerifyingOtp(true);
        setOtpError('');

        try {
            const formData = new URLSearchParams();
            formData.append('action', 'customer_verify_otp');
            formData.append('phone', phone);
            formData.append('otp', otpValue);
            formData.append('nonce', window.generic_data.nonces?.verify_otp || '');

            const response = await fetch(window.generic_data.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData.toString()
            });

            const data = await response.json();
            if (data.success) {
                if (window.toastr) {
                    window.toastr.success('Xác thực đăng nhập thành công!', 'Thành công');
                }
                setIsOpen(false);
                window.location.reload();
            } else {
                setOtpError(data.data?.message || 'Mã OTP không chính xác hoặc đã hết hạn.');
                setOtp(['', '', '', '', '', '']);
                if (otpInputsRef.current[0]) otpInputsRef.current[0].focus();
            }
        } catch (err) {
            setOtpError('Lỗi kết nối. Vui lòng kiểm tra lại mạng.');
            console.error('Verify OTP Error:', err);
        } finally {
            setIsVerifyingOtp(false);
        }
    };

    const handleOtpKeyDown = (e, index) => {
        if (e.key === 'Backspace' || e.key === 'Delete') {
            e.preventDefault();
            const newOtp = [...otp];
            newOtp[index] = '';
            setOtp(newOtp);

            // Move to previous field
            if (index > 0 && otpInputsRef.current[index - 1]) {
                otpInputsRef.current[index - 1].focus();
            }
        }
    };

    const handleOtpChange = (e, index) => {
        const val = e.target.value;
        if (!/^[0-9]?$/.test(val)) return;

        const newOtp = [...otp];
        newOtp[index] = val;
        setOtp(newOtp);

        // Auto focus next input
        if (val !== '' && index < 5 && otpInputsRef.current[index + 1]) {
            otpInputsRef.current[index + 1].focus();
        }

        // If complete, verify automatically
        const finalOtpValue = newOtp.join('');
        if (finalOtpValue.length === 6) {
            handleVerifyOtp(finalOtpValue);
        }
    };

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 z-[99999] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm animate-fade-in">
            {/* Modal Container */}
            <div className="relative w-full max-w-[480px] overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 transform scale-100 flex flex-col">
                
                {/* Header Back Button & Close */}
                <div className="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    {step !== 'method' ? (
                        <button 
                            type="button"
                            onClick={() => setStep(step === 'otp' ? 'phone' : 'method')} 
                            className="flex items-center justify-center w-8 h-8 rounded-full text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-colors"
                        >
                            <i className="fas fa-arrow-left"></i>
                        </button>
                    ) : <div className="w-8"></div>}
                    
                    <span className="font-semibold text-slate-800 text-lg">Đăng nhập tài khoản</span>

                    <button 
                        type="button"
                        onClick={() => setIsOpen(false)} 
                        className="flex items-center justify-center w-8 h-8 rounded-full text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-colors"
                    >
                        <i className="fas fa-times"></i>
                    </button>
                </div>

                {/* Body Content */}
                <div className="p-6 overflow-y-auto max-h-[85vh]">
                    
                    {/* Brand Banner */}
                    <div className="flex flex-col items-center mb-6">
                        <img 
                            src="https://object.dailyve.com/dailyve/wp-content/uploads/2024/10/DailyVe-12-300x104.png" 
                            alt="Dailyve" 
                            className="h-12 w-auto object-contain mb-3"
                        />
                        <div className="text-xs text-slate-400 bg-slate-100 px-3 py-1 rounded-full font-medium">Đặt vé xe khách, máy bay, tàu hỏa</div>
                    </div>

                    {/* Step 1: Select Method */}
                    {step === 'method' && (
                        <div className="flex flex-col items-stretch space-y-4 animate-slide-up">
                            <div className="text-center mb-2">
                                <h3 className="text-base font-bold text-slate-800 uppercase tracking-wide">ĐĂNG NHẬP HOẶC TẠO TÀI KHOẢN</h3>
                                <p className="text-sm text-slate-500 mt-1 leading-relaxed">
                                    Đặt vé cực nhanh và quản lý hành trình dễ dàng hơn với tài khoản Dailyve cá nhân.
                                </p>
                            </div>

                            <button 
                                type="button"
                                onClick={() => setStep('phone')}
                                className="flex items-center justify-center space-x-3 w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-blue-500/10 hover:shadow-blue-600/20 active:scale-[0.98] transition-all"
                            >
                                <i className="fas fa-sms text-lg"></i>
                                <span>Tiếp tục với SMS / Zalo</span>
                            </button>

                            <div className="flex items-center my-4">
                                <div className="flex-grow border-t border-slate-100"></div>
                                <span className="px-3 text-xs text-slate-400 font-medium uppercase tracking-wider">Hoặc đồng ý với</span>
                                <div className="flex-grow border-t border-slate-100"></div>
                            </div>

                            <p className="text-xs text-center text-slate-400 leading-relaxed">
                                Bằng cách đăng nhập, bạn đồng ý với <a href="/dieu-khoan/" className="text-blue-500 hover:underline">Điều khoản dịch vụ</a> và <a href="/bao-mat/" className="text-blue-500 hover:underline">Chính sách bảo mật</a> của chúng tôi.
                            </p>
                        </div>
                    )}

                    {/* Step 2: Input Phone Number */}
                    {step === 'phone' && (
                        <form onSubmit={handleSendOtp} className="flex flex-col items-stretch space-y-4 animate-slide-up">
                            <div className="flex flex-col space-y-1.5">
                                <label className="text-sm font-semibold text-slate-700">Nhập số điện thoại của bạn</label>
                                <div className="relative">
                                    <span className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-semibold">+84</span>
                                    <input 
                                        type="tel"
                                        value={phone}
                                        onChange={handlePhoneChange}
                                        placeholder="Nhập số điện thoại (ví dụ: 0912xxxxxx)"
                                        className={`w-full pl-14 pr-4 py-3 rounded-xl border ${phoneError ? 'border-red-500 bg-red-50/10 focus:border-red-500 focus:ring-red-200' : 'border-slate-200 focus:border-blue-500 focus:ring-blue-100'} outline-none focus:ring-4 font-medium transition-all`}
                                        disabled={isSendingOtp}
                                        autoFocus
                                    />
                                </div>
                                {phoneError && <span className="text-xs font-medium text-red-500 animate-fade-in"><i className="fas fa-exclamation-circle mr-1"></i>{phoneError}</span>}
                            </div>

                            <button 
                                type="submit"
                                disabled={isSendingOtp || !validatePhone(phone)}
                                className="w-full bg-blue-500 hover:bg-blue-600 disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-blue-500/10 transition-all flex items-center justify-center space-x-2"
                            >
                                {isSendingOtp ? (
                                    <>
                                        <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Đang gửi mã...</span>
                                    </>
                                ) : (
                                    <span>Gửi mã OTP</span>
                                )}
                            </button>
                        </form>
                    )}

                    {/* Step 3: Verify OTP */}
                    {step === 'otp' && (
                        <div className="flex flex-col items-stretch space-y-5 animate-slide-up">
                            <div className="text-center">
                                <h3 className="text-base font-bold text-slate-800">NHẬP MÃ XÁC THỰC OTP</h3>
                                <p className="text-sm text-slate-500 mt-1">
                                    Mã OTP đã được gửi đến số điện thoại <span className="font-semibold text-slate-700">{phone}</span> qua SMS/Zalo.
                                </p>
                                <p className="text-xs text-blue-600 bg-blue-50/50 px-3 py-1.5 rounded-lg inline-block mt-2 font-medium">
                                    <i className="far fa-clock mr-1"></i>Hết hạn sau: {formatTime(countdown)}
                                </p>
                            </div>

                            {/* OTP Code Fields */}
                            <div className="flex justify-between gap-2 max-w-[320px] mx-auto w-full">
                                {otp.map((digit, i) => (
                                    <input 
                                        key={i}
                                        ref={(el) => (otpInputsRef.current[i] = el)}
                                        type="tel"
                                        maxLength="1"
                                        value={digit}
                                        onKeyDown={(e) => handleOtpKeyDown(e, i)}
                                        onChange={(e) => handleOtpChange(e, i)}
                                        className={`w-11 h-12 text-center text-xl font-bold rounded-lg border focus:ring-4 outline-none ${otpError ? 'border-red-500 focus:border-red-500 focus:ring-red-100 bg-red-50/10' : 'border-slate-200 focus:border-blue-500 focus:ring-blue-100'} transition-all`}
                                        disabled={isVerifyingOtp}
                                    />
                                ))}
                            </div>

                            {otpError && <span className="text-xs font-semibold text-red-500 text-center animate-fade-in"><i className="fas fa-exclamation-circle mr-1"></i>{otpError}</span>}

                            {isVerifyingOtp && (
                                <div className="flex items-center justify-center space-x-2 text-slate-500 text-xs font-medium py-1">
                                    <svg className="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Đang xác thực OTP...</span>
                                </div>
                            )}

                            {/* Resend Option */}
                            <div className="text-center pt-2">
                                {countdown === 0 ? (
                                    <button 
                                        type="button"
                                        onClick={handleSendOtp}
                                        disabled={isSendingOtp}
                                        className="text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-all"
                                    >
                                        <i className="fas fa-redo mr-1"></i>Gửi lại mã OTP
                                    </button>
                                ) : (
                                    <span className="text-xs text-slate-400 font-medium">Bạn có thể gửi lại mã sau {countdown}s</span>
                                )}
                            </div>
                        </div>
                    )}

                </div>
            </div>
        </div>
    );
};

export default AuthModal;
