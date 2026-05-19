import React, { useState, useEffect, useRef } from 'react';

const TicketLookup = () => {
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [phone, setPhone] = useState('');
    const [status, setStatus] = useState('2'); // '1': Chưa thanh toán, '2': Đã thanh toán, '3,5': Đã hủy
    const [page, setPage] = useState(1);
    const [ticketsHtml, setTicketsHtml] = useState('');
    const [paginationHtml, setPaginationHtml] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [errorMsg, setErrorMsg] = useState('');

    // Refund Modal state
    const [refundModal, setRefundModal] = useState({
        isOpen: false,
        isLoading: false,
        postId: null,
        bookingCode: '',
        partner: '',
        allowCancel: false,
        allowMessage: '',
        cancelFee: 0,
        refundAmount: 0,
        refundBefore: '',
        statusDescription: '',
        reason: 'Khách hàng yêu cầu hủy vé',
        isConfirming: false,
        confirmError: '',
        confirmSuccess: ''
    });

    const ticketListRef = useRef(null);

    useEffect(() => {
        if (window.generic_data) {
            const loggedIn = window.generic_data.is_logged_in;
            setIsLoggedIn(loggedIn);
            if (loggedIn && window.generic_data.customer_data) {
                setPhone(window.generic_data.customer_data.phone || '');
            }
        }
    }, []);

    // Load tickets when tab or page changes, or after phone is loaded
    useEffect(() => {
        if (isLoggedIn && phone) {
            fetchTickets();
        }
    }, [isLoggedIn, phone, status, page]);

    // Handle pagination click delegation
    useEffect(() => {
        const handlePaginationClick = (e) => {
            const link = e.target.closest('.pagination-link');
            if (link) {
                e.preventDefault();
                const targetPage = parseInt(link.getAttribute('data-page'), 10);
                if (targetPage && targetPage !== page) {
                    setPage(targetPage);
                }
            }
        };

        const container = ticketListRef.current;
        if (container) {
            container.addEventListener('click', handlePaginationClick);
        }
        return () => {
            if (container) {
                container.removeEventListener('mousedown', handlePaginationClick);
            }
        };
    }, [ticketsHtml, page]);

    // Bind event handlers for dynamic HTML buttons (Refund button)
    useEffect(() => {
        const handleListClick = (e) => {
            const btn = e.target.closest('.btn-refund-ticket');
            if (btn) {
                e.preventDefault();
                const postId = btn.getAttribute('data-post-id');
                const bookingCode = btn.getAttribute('data-booking-code');
                const partner = btn.getAttribute('data-partner-id');
                openRefundModal(postId, bookingCode, partner);
            }
        };

        const container = ticketListRef.current;
        if (container) {
            container.addEventListener('click', handleListClick);
        }
        return () => {
            if (container) {
                container.removeEventListener('click', handleListClick);
            }
        };
    }, [ticketsHtml]);

    const fetchTickets = async () => {
        setIsLoading(true);
        setErrorMsg('');
        try {
            const formData = new URLSearchParams();
            formData.append('action', 'ticket_pagination');
            formData.append('page', page.toString());
            formData.append('per_page', '10');
            formData.append('status', status);
            formData.append('phone', phone);
            formData.append('nonce', window.generic_data.nonces?.auth || '');

            const response = await fetch(window.generic_data.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData.toString()
            });

            const data = await response.json();
            if (data.success) {
                setTicketsHtml(data.tickets || '');
                setPaginationHtml(data.pagination || '');
            } else {
                setErrorMsg('Không thể tải danh sách vé. Vui lòng tải lại trang.');
            }
        } catch (err) {
            setErrorMsg('Lỗi kết nối mạng. Vui lòng kiểm tra lại kết nối.');
            console.error('Fetch Tickets Error:', err);
        } finally {
            setIsLoading(false);
        }
    };

    const handleTabChange = (newStatus) => {
        setStatus(newStatus);
        setPage(1);
    };

    const formatVND = (amount) => {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    };

    const openRefundModal = async (postId, bookingCode, partner) => {
        setRefundModal(prev => ({
            ...prev,
            isOpen: true,
            isLoading: true,
            postId,
            bookingCode,
            partner,
            confirmError: '',
            confirmSuccess: '',
            reason: 'Khách hàng yêu cầu hủy vé'
        }));

        try {
            const formData = new URLSearchParams();
            formData.append('action', 'preview_refund_ticket');
            formData.append('post_id', postId);
            formData.append('nonce', window.generic_data.nonces?.auth || '');

            const response = await fetch(window.generic_data.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData.toString()
            });

            const data = await response.json();
            if (data.success) {
                const info = data.data;
                setRefundModal(prev => ({
                    ...prev,
                    isLoading: false,
                    allowCancel: info.allow_cancel,
                    allowMessage: info.allow_message,
                    cancelFee: info.cancel_fee || 0,
                    refundAmount: info.refund_amount || 0,
                    refundBefore: info.refund_before || '',
                    statusDescription: info.status_description || ''
                }));
            } else {
                setRefundModal(prev => ({
                    ...prev,
                    isLoading: false,
                    allowCancel: false,
                    allowMessage: data.data?.message || 'Không thể xem trước thông tin hủy vé.'
                }));
            }
        } catch (err) {
            setRefundModal(prev => ({
                ...prev,
                isLoading: false,
                allowCancel: false,
                allowMessage: 'Lỗi kết nối máy chủ khi tính phí hủy.'
            }));
            console.error('Preview Refund Error:', err);
        }
    };

    const handleConfirmRefund = async () => {
        setRefundModal(prev => ({ ...prev, isConfirming: true, confirmError: '' }));
        try {
            const formData = new URLSearchParams();
            formData.append('action', 'confirm_refund_ticket');
            formData.append('post_id', refundModal.postId);
            formData.append('reason', refundModal.reason);
            formData.append('nonce', window.generic_data.nonces?.auth || '');

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
                    window.toastr.success('Hủy vé & hoàn tiền thành công!', 'Thành công');
                }
                setRefundModal(prev => ({
                    ...prev,
                    isConfirming: false,
                    confirmSuccess: 'Đã thực hiện hủy vé hoàn tiền thành công. Tiền sẽ được hoàn lại theo chính sách.'
                }));
                
                // Refresh list after 1.5s
                setTimeout(() => {
                    setRefundModal(prev => ({ ...prev, isOpen: false }));
                    fetchTickets();
                }, 1800);
            } else {
                setRefundModal(prev => ({
                    ...prev,
                    isConfirming: false,
                    confirmError: data.data?.message || 'Hủy vé thất bại. Vui lòng liên hệ hotline hỗ trợ.'
                }));
            }
        } catch (err) {
            setRefundModal(prev => ({
                ...prev,
                isConfirming: false,
                confirmError: 'Lỗi hệ thống. Vui lòng kiểm tra lại kết nối mạng.'
            }));
            console.error('Confirm Refund Error:', err);
        }
    };

    const handleLoginTrigger = () => {
        window.dispatchEvent(new CustomEvent('show-login-modal'));
    };

    if (!isLoggedIn) {
        return (
            <div className="dailyve-account-card flex flex-col items-center justify-center p-8 bg-white rounded-2xl shadow-sm border border-slate-100/80 max-w-md mx-auto my-12 text-center animate-fade-in">
                <div className="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4 text-2xl">
                    <i className="fas fa-ticket-alt"></i>
                </div>
                <h3 className="text-lg font-bold text-slate-800">Yêu cầu đăng nhập</h3>
                <p className="text-slate-500 text-sm mt-2 mb-6 leading-relaxed">
                    Vui lòng đăng nhập tài khoản Dailyve để xem danh sách vé đã đặt và thực hiện hủy vé hoàn tiền.
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
        <div className="dailyve-account-card bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-slate-100/80 animate-fade-in flex flex-col" ref={ticketListRef}>
            
            {/* Header Tabs */}
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 pb-4 mb-6 gap-4">
                <div>
                    <h2 className="text-xl font-bold text-slate-800">Đơn hàng của tôi</h2>
                    <p className="text-sm text-slate-400 mt-1">Danh sách vé xe khách, máy bay, tàu hỏa đã đặt của bạn.</p>
                </div>
                
                {/* Tabs */}
                <div className="flex bg-slate-100/80 p-1 rounded-xl w-fit border border-slate-200/20">
                    <button
                        onClick={() => handleTabChange('2')}
                        className={`px-4 py-2 text-xs md:text-sm font-semibold rounded-lg transition-all cursor-pointer ${status === '2' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}
                    >
                        Đã thanh toán
                    </button>
                    <button
                        onClick={() => handleTabChange('1')}
                        className={`px-4 py-2 text-xs md:text-sm font-semibold rounded-lg transition-all cursor-pointer ${status === '1' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}
                    >
                        Chưa thanh toán
                    </button>
                    <button
                        onClick={() => handleTabChange('3,5')}
                        className={`px-4 py-2 text-xs md:text-sm font-semibold rounded-lg transition-all cursor-pointer ${status === '3,5' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}
                    >
                        Đã hủy
                    </button>
                </div>
            </div>

            {/* Error Message */}
            {errorMsg && (
                <div className="p-4 mb-6 bg-red-50 border border-red-100 text-red-600 text-sm font-medium rounded-xl flex items-center space-x-2">
                    <i className="fas fa-exclamation-circle"></i>
                    <span>{errorMsg}</span>
                </div>
            )}

            {/* Ticket Content Area */}
            <div className="relative min-h-[200px] flex flex-col">
                {isLoading && (
                    <div className="absolute inset-0 bg-white/70 flex items-center justify-center z-10 backdrop-blur-[1px]">
                        <div className="flex flex-col items-center space-y-2">
                            <svg className="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span className="text-xs font-semibold text-slate-500">Đang tải danh sách vé...</span>
                        </div>
                    </div>
                )}

                {/* Rendered HTML from Core functions */}
                <div 
                    className="ticket-lookup-results flex-grow"
                    dangerouslySetInnerHTML={{ __html: ticketsHtml || '<p class="text-center py-8 text-slate-400 font-medium">Không tìm thấy vé nào trong trạng thái này.</p>' }}
                />

                {/* Rendered Pagination */}
                {paginationHtml && (
                    <div 
                        className="mt-6 flex justify-center border-t border-slate-100 pt-5"
                        dangerouslySetInnerHTML={{ __html: paginationHtml }}
                    />
                )}
            </div>

            {/* Premium Refund Modal */}
            {refundModal.isOpen && (
                <div className="dailyve-ticket-modal fixed inset-0 z-[99999] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm animate-fade-in">
                    <div className="relative w-full max-w-[480px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                        {/* Modal Header */}
                        <div className="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <span className="font-bold text-slate-800 text-lg flex items-center"><i className="fas fa-ticket-alt text-red-500 mr-2"></i>Yêu cầu hủy vé</span>
                            <button 
                                onClick={() => setRefundModal(prev => ({ ...prev, isOpen: false }))} 
                                className="flex items-center justify-center w-8 h-8 rounded-full text-slate-400 hover:bg-slate-200/60 hover:text-slate-800 transition-all cursor-pointer"
                                disabled={refundModal.isConfirming}
                            >
                                <i className="fas fa-times"></i>
                            </button>
                        </div>

                        {/* Modal Body */}
                        <div className="p-6 overflow-y-auto flex-grow space-y-4">
                            {refundModal.isLoading ? (
                                <div className="py-12 flex flex-col items-center justify-center space-y-3">
                                    <svg className="animate-spin h-10 w-10 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span className="text-sm font-semibold text-slate-500">Đang tính toán phí hủy từ API đối tác...</span>
                                </div>
                            ) : (
                                <div className="space-y-4 animate-slide-up">
                                    {/* Success Message */}
                                    {refundModal.confirmSuccess && (
                                        <div className="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-semibold flex items-start space-x-2">
                                            <i className="fas fa-check-circle mt-0.5 text-lg"></i>
                                            <span>{refundModal.confirmSuccess}</span>
                                        </div>
                                    )}

                                    {/* Error Message */}
                                    {refundModal.confirmError && (
                                        <div className="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-semibold flex items-start space-x-2">
                                            <i className="fas fa-exclamation-circle mt-0.5 text-lg"></i>
                                            <span>{refundModal.confirmError}</span>
                                        </div>
                                    )}

                                    {!refundModal.confirmSuccess && (
                                        <>
                                            {/* Details Info */}
                                            <div className="bg-slate-50 rounded-xl p-4 border border-slate-200/50 space-y-2.5">
                                                <div className="flex justify-between text-sm">
                                                    <span className="text-slate-400 font-medium">Mã đặt vé:</span>
                                                    <span className="text-slate-800 font-bold">{refundModal.bookingCode}</span>
                                                </div>
                                                <div className="flex justify-between text-sm">
                                                    <span className="text-slate-400 font-medium">Đối tác vận hành:</span>
                                                    <span className="text-slate-800 font-semibold uppercase">{refundModal.partner}</span>
                                                </div>
                                            </div>

                                            {refundModal.allowCancel ? (
                                                <>
                                                    {/* Calculations Display */}
                                                    <div className="grid grid-cols-2 gap-4">
                                                        <div className="bg-red-50/50 rounded-xl p-4 border border-red-100 flex flex-col">
                                                            <span className="text-xs text-red-400 font-semibold uppercase tracking-wider">Phí hủy vé</span>
                                                            <span className="text-lg font-bold text-red-600 mt-1">{formatVND(refundModal.cancelFee)}</span>
                                                        </div>
                                                        <div className="bg-emerald-50/50 rounded-xl p-4 border border-emerald-100 flex flex-col">
                                                            <span className="text-xs text-emerald-400 font-semibold uppercase tracking-wider">Số tiền hoàn</span>
                                                            <span className="text-lg font-bold text-emerald-600 mt-1">{formatVND(refundModal.refundAmount)}</span>
                                                        </div>
                                                    </div>

                                                    {/* Deadlines */}
                                                    {refundModal.refundBefore && (
                                                        <div className="bg-blue-50/50 rounded-xl p-3 border border-blue-100 text-xs text-blue-700 font-medium flex items-center space-x-2">
                                                            <i className="far fa-clock text-sm"></i>
                                                            <span>Hạn chót hủy vé trước: <strong>{refundModal.refundBefore}</strong></span>
                                                        </div>
                                                    )}

                                                    {/* Reason Form */}
                                                    <div className="flex flex-col space-y-1.5 pt-2">
                                                        <label className="text-sm font-semibold text-slate-700">Lý do hủy vé</label>
                                                        <textarea 
                                                            rows="2"
                                                            value={refundModal.reason}
                                                            onChange={(e) => setRefundModal(prev => ({ ...prev, reason: e.target.value }))}
                                                            placeholder="Vui lòng nhập lý do hủy vé..."
                                                            className="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none font-medium transition-all text-sm text-slate-700"
                                                            disabled={refundModal.isConfirming}
                                                        />
                                                    </div>

                                                    {/* Confirm Button */}
                                                    <div className="pt-4 border-t border-slate-50 flex items-center justify-end space-x-3">
                                                        <button 
                                                            onClick={() => setRefundModal(prev => ({ ...prev, isOpen: false }))}
                                                            className="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-500 font-semibold text-sm hover:bg-slate-50 hover:text-slate-800 transition-all cursor-pointer"
                                                            disabled={refundModal.isConfirming}
                                                        >
                                                            Hủy bỏ
                                                        </button>
                                                        <button 
                                                            onClick={handleConfirmRefund}
                                                            className="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-red-500/10 active:scale-[0.98] transition-all flex items-center space-x-2 cursor-pointer"
                                                            disabled={refundModal.isConfirming}
                                                        >
                                                            {refundModal.isConfirming ? (
                                                                <>
                                                                    <svg className="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                    </svg>
                                                                    <span>Đang hủy vé...</span>
                                                                </>
                                                            ) : (
                                                                <span>Xác nhận hủy & Hoàn tiền</span>
                                                            )}
                                                        </button>
                                                    </div>
                                                </>
                                            ) : (
                                                <>
                                                    {/* Cancellation Restriction Notification */}
                                                    <div className="bg-red-50 border border-red-100 rounded-xl p-5 text-center flex flex-col items-center space-y-3">
                                                        <div className="w-12 h-12 bg-red-100 text-red-500 rounded-full flex items-center justify-center text-xl">
                                                            <i className="fas fa-exclamation-triangle animate-pulse"></i>
                                                        </div>
                                                        <h4 className="font-bold text-slate-800">Không đủ điều kiện hủy vé</h4>
                                                        <p className="text-sm text-red-700 leading-relaxed font-semibold">
                                                            {refundModal.allowMessage}
                                                        </p>
                                                        {refundModal.statusDescription && (
                                                            <p className="text-xs text-slate-500 italic mt-1">Trạng thái API: {refundModal.statusDescription}</p>
                                                        )}
                                                    </div>

                                                    {/* Close Button */}
                                                    <div className="pt-2 border-t border-slate-50 flex justify-end">
                                                        <button 
                                                            onClick={() => setRefundModal(prev => ({ ...prev, isOpen: false }))}
                                                            className="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-sm rounded-xl active:scale-[0.98] transition-all cursor-pointer"
                                                        >
                                                            Đóng lại
                                                        </button>
                                                    </div>
                                                </>
                                            )}
                                        </>
                                    )}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            )}

        </div>
    );
};

export default TicketLookup;
