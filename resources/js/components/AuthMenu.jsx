import React, { useState, useEffect, useRef } from 'react';

const AuthMenu = () => {
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [customerData, setCustomerData] = useState(null);
    const [isOpen, setIsOpen] = useState(false);
    const menuRef = useRef(null);

    useEffect(() => {
        if (window.generic_data) {
            setIsLoggedIn(window.generic_data.is_logged_in || false);
            setCustomerData(window.generic_data.customer_data || null);
        }

        // Close dropdown when clicking outside
        const handleClickOutside = (event) => {
            if (menuRef.current && !menuRef.current.contains(event.target)) {
                setIsOpen(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    const toggleMenu = () => {
        setIsOpen(!isOpen);
    };

    const handleLoginClick = (e) => {
        e.preventDefault();
        window.dispatchEvent(new CustomEvent('show-login-modal'));
    };

    const getLogoutUrl = () => {
        if (window.generic_data && window.generic_data.ajax_url) {
            const homeUrl = window.location.origin;
            return `${homeUrl}?action=customer_logout`;
        }
        return '#';
    };

    if (!isLoggedIn) {
        return (
            <div className="dailyve-auth-menu flex items-center space-x-3">
                <button 
                    onClick={handleLoginClick}
                    className="flex items-center space-x-2 text-slate-700 hover:text-blue-600 font-semibold text-sm py-2 px-4 rounded-full border border-slate-200 hover:border-blue-100 hover:bg-blue-50/20 active:scale-95 transition-all cursor-pointer"
                >
                    <i className="far fa-user text-xs"></i>
                    <span>Đăng nhập / Đăng ký</span>
                </button>
            </div>
        );
    }

    const phone = customerData?.phone || '';
    const avatar = customerData?.avatar || '/wp-content/uploads/images/user.png';

    return (
        <div className="dailyve-auth-menu relative inline-block text-left" ref={menuRef}>
            {/* User Profile Trigger Button */}
            <button
                onClick={toggleMenu}
                className="flex items-center space-x-2.5 bg-slate-50 hover:bg-slate-100/80 active:scale-[0.98] border border-slate-200/60 rounded-full py-1.5 pl-2 pr-3.5 transition-all focus:outline-none cursor-pointer"
            >
                <img 
                    src={avatar} 
                    alt="Avatar" 
                    className="w-7 h-7 rounded-full object-cover shadow-sm border border-white"
                    onError={(e) => {
                        e.target.src = '/wp-content/uploads/images/user.png';
                    }}
                />
                <span className="text-sm font-semibold text-slate-700">{phone}</span>
                <i className={`fas fa-caret-down text-slate-400 text-xs transition-transform duration-250 ${isOpen ? 'rotate-180 text-blue-500' : ''}`}></i>
            </button>

            {/* Dropdown Menu */}
            {isOpen && (
                <div className="absolute right-0 mt-2.5 w-56 origin-top-right rounded-xl bg-white shadow-xl ring-1 ring-black/5 focus:outline-none z-[1000] overflow-hidden animate-slide-down">
                    <div className="px-4 py-3 border-b border-slate-50 bg-slate-50/50">
                        <p className="text-xs font-medium text-slate-400">Tài khoản Dailyve</p>
                        <p className="text-sm font-bold text-slate-700 truncate mt-0.5">{phone}</p>
                    </div>
                    
                    <div className="py-1">
                        <a
                            href="/tai-khoan"
                            className="flex items-center space-x-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors"
                            onClick={() => setIsOpen(false)}
                        >
                            <i className="far fa-user text-slate-400 w-4 text-center"></i>
                            <span>Thông tin tài khoản</span>
                        </a>
                        
                        <a
                            href="/don-hang-cua-toi"
                            className="flex items-center space-x-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors"
                            onClick={() => setIsOpen(false)}
                        >
                            <i className="fas fa-ticket-alt text-slate-400 w-4 text-center"></i>
                            <span>Đơn hàng của tôi</span>
                        </a>

                        <a
                            href="/uu-dai"
                            className="flex items-center space-x-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors"
                            onClick={() => setIsOpen(false)}
                        >
                            <i className="fas fa-gift text-slate-400 w-4 text-center"></i>
                            <span>Ưu đãi</span>
                        </a>

                        <a
                            href="/quan-ly-the"
                            className="flex items-center space-x-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors"
                            onClick={() => setIsOpen(false)}
                        >
                            <i className="far fa-credit-card text-slate-400 w-4 text-center"></i>
                            <span>Quản lý thẻ</span>
                        </a>
                    </div>

                    <div className="border-t border-slate-100 py-1 bg-red-50/10">
                        <a
                            href={getLogoutUrl()}
                            className="flex items-center space-x-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors font-medium"
                        >
                            <i className="fas fa-sign-out-alt text-red-400 w-4 text-center"></i>
                            <span>Đăng xuất</span>
                        </a>
                    </div>
                </div>
            )}
        </div>
    );
};

export default AuthMenu;
