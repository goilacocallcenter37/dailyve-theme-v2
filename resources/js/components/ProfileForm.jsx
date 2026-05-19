import React, { useState, useEffect } from 'react';

const ProfileForm = () => {
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');
    const [birthDate, setBirthDate] = useState('');
    const [gender, setGender] = useState('other');
    const [isUpdating, setIsUpdating] = useState(false);
    const [message, setMessage] = useState('');
    const [errorMsg, setErrorMsg] = useState('');

    useEffect(() => {
        if (window.generic_data) {
            const loggedIn = window.generic_data.is_logged_in;
            setIsLoggedIn(loggedIn);
            if (loggedIn && window.generic_data.customer_data) {
                const data = window.generic_data.customer_data;
                setName(data.name || '');
                setEmail(data.email || '');
                setPhone(data.phone || '');
                setGender(data.gender || 'other');
                
                if (data.birth_date) {
                    try {
                        const dateObj = new Date(data.birth_date);
                        if (!isNaN(dateObj.getTime())) {
                            setBirthDate(dateObj.toISOString().split('T')[0]);
                        }
                    } catch (e) {
                        console.error('Birth date parsing error:', e);
                    }
                }
            }
        }
    }, []);

    const handleLoginTrigger = () => {
        window.dispatchEvent(new CustomEvent('show-login-modal'));
    };

    const handleUpdateProfile = async (e) => {
        e.preventDefault();
        setIsUpdating(true);
        setMessage('');
        setErrorMsg('');

        try {
            const formData = new URLSearchParams();
            formData.append('action', 'update_customer_profile');
            formData.append('name', name);
            formData.append('email', email);
            formData.append('birth_date', birthDate);
            formData.append('gender', gender);
            formData.append('profile_nonce', window.generic_data.nonces?.update_profile || '');

            const response = await fetch(window.generic_data.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData.toString()
            });

            const data = await response.json();
            if (data.success) {
                // Update generic_data in memory
                if (window.generic_data && window.generic_data.customer_data) {
                    window.generic_data.customer_data.name = name;
                    window.generic_data.customer_data.email = email;
                    window.generic_data.customer_data.gender = gender;
                    window.generic_data.customer_data.birth_date = birthDate;
                }
                
                if (window.toastr) {
                    window.toastr.success('Cập nhật thông tin cá nhân thành công!', 'Thành công');
                }
                setMessage('Cập nhật thông tin thành công!');
            } else {
                setErrorMsg(data.data?.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
                if (window.toastr) {
                    window.toastr.error(data.data?.message || 'Cập nhật thất bại', 'Thất bại');
                }
            }
        } catch (err) {
            setErrorMsg('Lỗi kết nối máy chủ. Vui lòng thử lại.');
            console.error('Profile Update Error:', err);
        } finally {
            setIsUpdating(false);
        }
    };

    if (!isLoggedIn) {
        return (
            <div className="dailyve-account-card flex flex-col items-center justify-center p-8 bg-white rounded-2xl shadow-sm border border-slate-100/80 max-w-md mx-auto my-12 text-center animate-fade-in">
                <div className="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4 text-2xl">
                    <i className="far fa-user"></i>
                </div>
                <h3 className="text-lg font-bold text-slate-800">Yêu cầu đăng nhập</h3>
                <p className="text-slate-500 text-sm mt-2 mb-6 leading-relaxed">
                    Vui lòng đăng nhập tài khoản Dailyve để xem và quản lý thông tin cá nhân của bạn.
                </p>
                <button
                    onClick={handleLoginTrigger}
                    className="bg-blue-500 hover:bg-blue-600 active:scale-95 text-white font-semibold py-2.5 px-6 rounded-xl shadow-lg shadow-blue-500/10 transition-all cursor-pointer"
                >
                    Đăng nhập ngay
                </button>
            </div>
        );
    }

    return (
        <div className="dailyve-account-card bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-slate-100/80 animate-fade-in">
            <div className="border-b border-slate-100 pb-5 mb-6">
                <h2 className="text-xl font-bold text-slate-800">Thông tin tài khoản</h2>
                <p className="text-sm text-slate-400 mt-1">Cập nhật thông tin cá nhân của bạn để nhận dịch vụ tốt nhất.</p>
            </div>

            {message && (
                <div className="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-semibold flex items-center space-x-2 animate-fade-in">
                    <i className="fas fa-check-circle"></i>
                    <span>{message}</span>
                </div>
            )}

            {errorMsg && (
                <div className="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-semibold flex items-center space-x-2 animate-fade-in">
                    <i className="fas fa-exclamation-circle"></i>
                    <span>{errorMsg}</span>
                </div>
            )}

            <form onSubmit={handleUpdateProfile} className="space-y-5">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {/* Full Name */}
                    <div className="flex flex-col space-y-1.5">
                        <label htmlFor="fullName" className="text-sm font-semibold text-slate-700">Họ và tên</label>
                        <input
                            type="text"
                            id="fullName"
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                            placeholder="Nhập họ và tên của bạn"
                            className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none font-medium transition-all text-slate-700"
                            required
                        />
                    </div>

                    {/* Email Address */}
                    <div className="flex flex-col space-y-1.5">
                        <label htmlFor="email" className="text-sm font-semibold text-slate-700">Địa chỉ Email</label>
                        <input
                            type="email"
                            id="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            placeholder="username@domain.com"
                            className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none font-medium transition-all text-slate-700"
                            required
                        />
                    </div>

                    {/* Phone Number (Disabled) */}
                    <div className="flex flex-col space-y-1.5">
                        <label htmlFor="phone" className="text-sm font-semibold text-slate-700">Số điện thoại</label>
                        <div className="relative">
                            <input
                                type="tel"
                                id="phone"
                                value={phone}
                                disabled
                                className="w-full px-4 py-2.5 rounded-xl border border-slate-100 bg-slate-50 text-slate-400 font-medium cursor-not-allowed outline-none"
                            />
                            <span className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold bg-white border border-slate-200 px-2 py-0.5 rounded-full"><i className="fas fa-lock mr-1"></i>Đã khóa</span>
                        </div>
                    </div>

                    {/* Birth Date */}
                    <div className="flex flex-col space-y-1.5">
                        <label htmlFor="birthDate" className="text-sm font-semibold text-slate-700">Ngày sinh</label>
                        <input
                            type="date"
                            id="birthDate"
                            value={birthDate}
                            onChange={(e) => setBirthDate(e.target.value)}
                            className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none font-medium transition-all text-slate-700"
                        />
                    </div>
                </div>

                {/* Gender Options */}
                <div className="flex flex-col space-y-2">
                    <label className="text-sm font-semibold text-slate-700">Giới tính</label>
                    <div className="flex items-center space-x-6">
                        <label className="inline-flex items-center space-x-2 text-slate-600 font-medium cursor-pointer">
                            <input
                                type="radio"
                                name="gender"
                                value="male"
                                checked={gender === 'male'}
                                onChange={() => setGender('male')}
                                className="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500 focus:ring-2"
                            />
                            <span>Nam</span>
                        </label>
                        <label className="inline-flex items-center space-x-2 text-slate-600 font-medium cursor-pointer">
                            <input
                                type="radio"
                                name="gender"
                                value="female"
                                checked={gender === 'female'}
                                onChange={() => setGender('female')}
                                className="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500 focus:ring-2"
                            />
                            <span>Nữ</span>
                        </label>
                        <label className="inline-flex items-center space-x-2 text-slate-600 font-medium cursor-pointer">
                            <input
                                type="radio"
                                name="gender"
                                value="other"
                                checked={gender === 'other'}
                                onChange={() => setGender('other')}
                                className="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500 focus:ring-2"
                            />
                            <span>Khác</span>
                        </label>
                    </div>
                </div>

                {/* Action Buttons */}
                <div className="flex items-center justify-end pt-4 border-t border-slate-50">
                    <button
                        type="submit"
                        disabled={isUpdating}
                        className="bg-blue-500 hover:bg-blue-600 disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none text-white font-semibold py-2.5 px-6 rounded-xl shadow-lg shadow-blue-500/10 active:scale-[0.98] transition-all flex items-center space-x-2 cursor-pointer"
                    >
                        {isUpdating ? (
                            <>
                                <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Đang cập nhật...</span>
                            </>
                        ) : (
                            <>
                                <i className="fas fa-save mr-1"></i>
                                <span>Cập nhật thông tin</span>
                            </>
                        )}
                    </button>
                </div>
            </form>
        </div>
    );
};

export default ProfileForm;
