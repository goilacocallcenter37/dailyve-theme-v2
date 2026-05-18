import React, { useState, useEffect, useMemo } from 'react';

const SeatIcon = ({ type, color, status, onClick }) => {
  const isSelected = status === 'selected';
  const isSold = status === 'sold';
  const fillColor = isSelected ? color : isSold ? '#CBD5E1' : '#FFFFFF';
  const strokeColor = isSold ? '#CBD5E1' : color;
  const detailFill = isSelected ? '#FFFFFF' : isSold ? '#CBD5E1' : '#F8FAFC';
  const detailStroke = isSelected ? '#FFFFFF' : isSold ? '#CBD5E1' : '#CBD5E1';
  const opacity = status === 'sold' ? '0.8' : '1';

  const renderPath = () => {
    switch (type) {
      case 7: // Double seat
        return (
          <svg width="44" height="36" viewBox="0 0 56 44" fill="none" className="block w-full h-auto max-w-[44px] mx-auto transition-all duration-300">
            <rect
              x="3"
              y="3"
              width="50"
              height="38"
              rx="7"
              fill={fillColor}
              stroke={strokeColor}
              strokeWidth="2.5"
            />
            <path
              d="M10 29H46"
              stroke={isSold ? '#CBD5E1' : strokeColor}
              strokeWidth="1.6"
              strokeLinecap="round"
              opacity={isSelected ? '0.32' : '0.18'}
            />
            <rect
              x="9"
              y="31"
              width="17"
              height="7"
              rx="2.4"
              fill={detailFill}
              stroke={isSelected || isSold ? detailStroke : strokeColor}
              strokeWidth="1.6"
            />
            <rect
              x="30"
              y="31"
              width="17"
              height="7"
              rx="2.4"
              fill={detailFill}
              stroke={isSelected || isSold ? detailStroke : strokeColor}
              strokeWidth="1.6"
            />
          </svg>
        );
      case 2: // Simple seat
      default:
        return (
          <svg width="34" height="42" viewBox="0 0 40 48" fill="none" className="block w-full h-auto max-w-[34px] mx-auto transition-all duration-300">
            <rect
              x="4"
              y="3"
              width="32"
              height="42"
              rx="7"
              fill={fillColor}
              stroke={strokeColor}
              strokeWidth="2.5"
            />
            <path
              d="M10 32H30"
              stroke={isSold ? '#CBD5E1' : strokeColor}
              strokeWidth="1.5"
              strokeLinecap="round"
              opacity={isSelected ? '0.32' : '0.18'}
            />
            <rect
              x="9"
              y="35"
              width="22"
              height="7"
              rx="2.4"
              fill={detailFill}
              stroke={isSelected || isSold ? detailStroke : strokeColor}
              strokeWidth="1.6"
            />
          </svg>
        );
    }
  };

  return (
    <div
      className={`relative cursor-pointer transition-transform hover:scale-110 ${status === 'sold' ? 'cursor-not-allowed opacity-50' : ''}`}
      onClick={status !== 'sold' ? onClick : undefined}
      style={{ opacity }}
    >
      {renderPath()}
      {status === 'selected' && (
        <div className="absolute inset-0 flex items-center justify-center text-white text-[10px]">
          <i className="fas fa-check"></i>
        </div>
      )}
    </div>
  );
};

const SeatSelection = ({ trip, onCancel, onComplete }) => {
  const [step, setStep] = useState(1);
  const [loading, setLoading] = useState(true);
  const [isFinalizing, setIsFinalizing] = useState(false);
  const [data, setData] = useState(null);
  const [selectedSeats, setSelectedSeats] = useState([]);
  const [selectedPickup, setSelectedPickup] = useState(null);
  const [selectedDropoff, setSelectedDropoff] = useState(null);
  const [pickupAddress, setPickupAddress] = useState('');
  const [dropoffAddress, setDropoffAddress] = useState('');
  const [seatGroupSelector, setSeatGroupSelector] = useState(null); // { seat, groups }

  useEffect(() => {
    const formData = new FormData();
    formData.append('action', 'choose_trip_ajax_booking');
    formData.append('partnerId', trip.partner?.partner_id || trip.partner_id || 'vexere');
    formData.append('tripCode', trip.trip_id);
    formData.append('departureTime', trip.pickup_date);
    formData.append('nonce', window.generic_data?.nonce);

    fetch(window.generic_data?.ajax_url || '/wp-admin/admin-ajax.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          setData(res.data.data);
        } else {
          console.error('Failed to load trip details:', res.data);
        }
      })
      .catch(err => console.error(err))
      .finally(() => setLoading(false));
  }, [trip]);

  const calculatePointSurcharge = (point, seatCount) => {
    if (!point) return 0;
    let surcharge = Number(point.surcharge || 0);
    const tiers = point.surcharge_tiers ? (typeof point.surcharge_tiers === 'string' ? JSON.parse(point.surcharge_tiers) : point.surcharge_tiers) : [];
    
    if (tiers.length > 0) {
      for (const tier of tiers) {
        const from = Number(tier.from);
        const to = tier.to != null ? Number(tier.to) : null;
        const tierSurcharge = Number(tier.surcharge);
        const unit = tier.unit;

        if (to != null) {
          if (seatCount >= from && seatCount <= to) {
            return unit === 'group' ? tierSurcharge : tierSurcharge * seatCount;
          }
        } else if (seatCount >= from) {
          return unit === 'group' ? tierSurcharge : tierSurcharge * seatCount;
        }
      }
    }
    return surcharge; // Default fallback (often per group or per ticket depending on API, but old theme used it as base)
  };

  const handleFinalize = async () => {
    if (!selectedPickup || !selectedDropoff || selectedSeats.length === 0) return;
    
    // Validate unfixed points
    if (selectedPickup.unfixed_point == 1 && !pickupAddress) {
      alert('Vui lòng nhập địa chỉ điểm đón');
      return;
    }
    if (selectedDropoff.unfixed_point == 1 && !dropoffAddress) {
      alert('Vui lòng nhập địa chỉ điểm trả');
      return;
    }

    setIsFinalizing(true);
    
    // Only keep essential info from data to reduce payload size
    const essentialData = {
      company_logo: data.company_logo,
      company_name: data.company_name,
      name: data.name
    };

    const ticket = {
      tripId: trip.trip_id,
      partnerId: trip.partner?.partner_id || trip.partner_id || 'vexere',
      selectedSeats: selectedSeats.map(s => ({
        seatCode: s.seat_code,
        full_code: s.full_code,
        fare: s.fare,
        group: s.seat_group_code || s.group // Compatibility
      })),
      pickupPoint: selectedPickup,
      dropoffPoint: selectedDropoff,
      pickupPointMoreDesc: pickupAddress,
      dropoffPointMoreDesc: dropoffAddress,
      pickupSurcharge: calculatePointSurcharge(selectedPickup, selectedSeats.length),
      dropoffSurcharge: calculatePointSurcharge(selectedDropoff, selectedSeats.length),
      departure_date: trip.departure_date || trip.pickup_date,
      departure_time: trip.departure_time || (trip.pickup_date ? trip.pickup_date.split('T')[1]?.slice(0, 5) : ''),
      seatsAndInfoData: essentialData
    };

    if (!window.generic_data?.nonce) {
      console.error('Missing generic_data or nonce');
      alert('Lỗi hệ thống: Thiếu mã xác thực (Nonce). Vui lòng tải lại trang.');
      setIsFinalizing(false);
      return;
    }

    try {
      const formData = new FormData();
      formData.append('action', 'save_ticket');
      formData.append('nonce', window.generic_data.nonce);
      
      formData.append('ticket[tripId]', ticket.tripId);
      formData.append('ticket[partnerId]', ticket.partnerId);
      formData.append('ticket[pickupPoint]', JSON.stringify(ticket.pickupPoint));
      formData.append('ticket[dropoffPoint]', JSON.stringify(ticket.dropoffPoint));
      formData.append('ticket[selectedSeats]', JSON.stringify(ticket.selectedSeats));
      formData.append('ticket[seatsAndInfoData]', JSON.stringify(ticket.seatsAndInfoData));
      formData.append('ticket[pickupPointMoreDesc]', ticket.pickupPointMoreDesc || '');
      formData.append('ticket[dropoffPointMoreDesc]', ticket.dropoffPointMoreDesc || '');
      formData.append('ticket[pickupSurcharge]', ticket.pickupSurcharge);
      formData.append('ticket[dropoffSurcharge]', ticket.dropoffSurcharge);
      formData.append('ticket[departure_date]', ticket.departure_date || '');
      formData.append('ticket[departure_time]', ticket.departure_time || '');

      const response = await fetch(window.generic_data.ajax_url || '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: formData
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const res = await response.json();
      if (res.success) {
        onComplete(ticket);
      } else {
        alert(res.data?.message || 'Không thể lưu thông tin vé');
      }
    } catch (err) {
      console.error('Save ticket error:', err);
      alert(`Lỗi kết nối khi lưu vé: ${err.message}`);
    } finally {
      setIsFinalizing(false);
    }
  };

  const toggleSeat = (seat, group = null) => {
    setSelectedSeats(prev => {
      const isSelected = prev.find(s => s.seat_code === seat.seat_code);
      if (isSelected) {
        return prev.filter(s => s.seat_code !== seat.seat_code);
      }
      
      const newSeat = { ...seat };
      if (group) {
        newSeat.fare = group.fare;
        newSeat.seat_group_code = group.seat_group_id || group.seat_group_code;
        newSeat.seat_group = group.seat_group;
      }
      return [...prev, newSeat];
    });
    setSeatGroupSelector(null);
  };

  const handleSeatClick = (seat) => {
    const isSelected = selectedSeats.find(s => s.seat_code === seat.seat_code);
    if (isSelected) {
      toggleSeat(seat);
      return;
    }

    if (seat.seat_groups && seat.seat_groups.length > 1) {
      setSeatGroupSelector({ seat, groups: seat.seat_groups });
    } else {
      toggleSeat(seat);
    }
  };

  const currentPickupSurcharge = useMemo(() => calculatePointSurcharge(selectedPickup, selectedSeats.length), [selectedPickup, selectedSeats.length]);
  const currentDropoffSurcharge = useMemo(() => calculatePointSurcharge(selectedDropoff, selectedSeats.length), [selectedDropoff, selectedSeats.length]);

  const totalPrice = useMemo(() => {
    const seatTotal = selectedSeats.reduce((sum, s) => sum + (s.fare || 0), 0);
    // Only add surcharge to total if it's "Pay Now" (type 2)
    const pSurcharge = (selectedPickup?.surcharge_type == 2) ? currentPickupSurcharge : 0;
    const dSurcharge = (selectedDropoff?.surcharge_type == 2) ? currentDropoffSurcharge : 0;
    return seatTotal + pSurcharge + dSurcharge;
  }, [selectedSeats, currentPickupSurcharge, currentDropoffSurcharge, selectedPickup, selectedDropoff]);

  if (loading) {
    return (
      <div className="flex items-center justify-center py-20">
        <div className="h-12 w-12 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
      </div>
    );
  }

  if (!data) return <div className="p-10 text-center text-danger font-bold">Không thể tải sơ đồ ghế. Vui lòng thử lại sau.</div>;

  const isPointDisabled = (point) => {
    // Check min_customer
    if (point.min_customer && selectedSeats.length < point.min_customer) return true;
    
    // Check transfer disabled time
    if (point.transfer_disabled_real_time) {
      const target = new Date(point.transfer_disabled_real_time.replace(/(\d{2}):(\d{2}) (\d{2})-(\d{2})-(\d{4})/, '$5-$4-$3T$1:$2:00'));
      if (target < new Date()) return true;
    }
    
    return false;
  };

  return (
    <div className="animate-in fade-in slide-in-from-top-4 duration-500">
      {/* Seat Group Selector Modal */}
      {seatGroupSelector && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm animate-in fade-in duration-300">
          <div className="w-full max-w-md overflow-hidden rounded-[2.5rem] bg-white shadow-2xl animate-in zoom-in-95 duration-300">
            <div className="bg-primary p-8 text-center text-white">
              <h3 className="font-display text-xl font-black">Chọn loại vé</h3>
              <p className="mt-1 text-sm opacity-80">Giường {seatGroupSelector.seat.seat_code} có nhiều lựa chọn giá</p>
            </div>
            <div className="p-8 space-y-4">
              {seatGroupSelector.groups.map((group, idx) => (
                <button
                  key={idx}
                  onClick={() => toggleSeat(seatGroupSelector.seat, group)}
                  className="flex w-full items-center justify-between rounded-2xl border-2 border-slate-100 p-5 transition-all hover:border-primary hover:bg-primary/5 active:scale-95"
                >
                  <div className="text-left">
                    <div className="font-bold text-slate-900">{group.seat_group}</div>
                    <div className="text-xs text-slate-400">Giá áp dụng cho loại này</div>
                  </div>
                  <div className="font-display text-lg font-black text-primary">
                    {(group.fare || 0).toLocaleString()}đ
                  </div>
                </button>
              ))}
              <button 
                onClick={() => setSeatGroupSelector(null)}
                className="mt-4 w-full py-2 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors"
              >
                Hủy bỏ
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Steps Header */}
      <div className="mb-6 flex items-center justify-between gap-3 border-b border-slate-100 pb-5 sm:mb-8 sm:pb-6">
        <div className="flex min-w-0 flex-wrap items-center gap-3 sm:gap-8">
          {[1, 2].map((s) => (
            <div 
              key={s} 
              className={`flex items-center gap-3 transition-all ${step === s ? 'opacity-100' : 'opacity-40'}`}
            >
              <div className={`flex h-10 w-10 items-center justify-center rounded-full font-black ${
                step === s ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-slate-200 text-slate-500'
              }`}>
                {s}
              </div>
              <span className="hidden text-sm font-black uppercase tracking-widest sm:block">
                {s === 1 ? 'Chọn ghế' : 'Điểm đón trả'}
              </span>
            </div>
          ))}
        </div>
        <button onClick={onCancel} className="text-slate-400 hover:text-danger transition-colors">
          <i className="fas fa-times text-xl"></i>
        </button>
      </div>

      <div className="grid gap-6 lg:grid-cols-[minmax(0,1fr)_350px] lg:gap-10">
        {/* Main Content Area */}
        <div className="min-w-0 space-y-8">
          {step === 1 && (
            <div className="space-y-8">
              <div className="flex flex-wrap gap-4 rounded-2xl bg-slate-50 p-4 shadow-inner sm:gap-6 sm:p-6 lg:gap-8 lg:p-8">
                {/* Sold Seats */}
                <div className="flex items-center gap-4">
                  <SeatIcon type={data.coach_seat_template[0]?.seats[0]?.seat_type || 2} color="#CBD5E1" status="sold" />
                  <div className="flex flex-col">
                    <span className="text-xs font-black text-slate-400 uppercase tracking-wider">Đã bán</span>
                    <span className="text-[10px] font-bold text-slate-300">Ghế không còn trống</span>
                  </div>
                </div>

                {/* Selected Seats */}
                <div className="flex items-center gap-4">
                  <div className="relative">
                    <SeatIcon type={data.coach_seat_template[0]?.seats[0]?.seat_type || 2} color="#2196F3" status="selected" />
                    <div className="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-primary text-[8px] text-white shadow-sm">
                      <i className="fas fa-check"></i>
                    </div>
                  </div>
                  <div className="flex flex-col">
                    <span className="text-xs font-black text-primary uppercase tracking-wider">Đang chọn</span>
                    <span className="text-[10px] font-bold text-slate-400">Chỗ bạn vừa click</span>
                  </div>
                </div>

                {/* Available Seat Groups */}
                {(() => {
                  const groups = {};
                  data.coach_seat_template.forEach(coach => {
                    coach.seats.forEach(seat => {
                      if (!groups[seat.seat_group_code]) {
                        groups[seat.seat_group_code] = {
                          name: seat.seat_group,
                          color: seat.seat_color,
                          type: seat.seat_type,
                          fare: seat.fare
                        };
                      }
                    });
                  });
                  return Object.values(groups).map((group, idx) => (
                    <div key={idx} className="flex items-center gap-4">
                      <SeatIcon type={group.type} color={group.color} status="available" />
                      <div className="flex flex-col">
                        <span className="text-xs font-bold text-slate-800 uppercase tracking-wider">{group.name}</span>
                        <span className="font-display text-[11px] font-black text-primary">{(group.fare || 0).toLocaleString()}đ</span>
                      </div>
                    </div>
                  ));
                })()}
              </div>

              <div className="flex flex-col items-center gap-8 py-4 sm:py-8 md:flex-row md:items-start md:justify-center">
                {data.coach_seat_template.map((coach, idx) => {
                  const maxGridWidth = coach.num_cols > 4 ? 'w-full max-w-[320px] sm:max-w-[360px]' : 'w-full max-w-[260px] sm:max-w-[280px]';
                  return (
                    <div key={idx} className={`${maxGridWidth} space-y-4`}>
                      <h4 className="text-center font-display text-sm font-black uppercase tracking-widest text-slate-400">{coach.coach_name}</h4>
                      <div className="w-full overflow-hidden">
                        <div
                          className="grid w-full gap-2 rounded-2xl border-4 border-slate-100 bg-slate-50/30 p-3 shadow-inner sm:gap-3 sm:p-5 lg:rounded-[2rem] lg:p-6"
                          style={{
                            gridTemplateColumns: `repeat(${coach.num_cols}, minmax(0, 1fr))`,
                            gridTemplateRows: `repeat(${coach.num_rows}, minmax(0, 1fr))`
                          }}
                        >
                          {coach.seats.map((seat, sIdx) => (
                            <div
                              key={sIdx}
                              style={{
                                gridArea: `${seat.row_num} / ${seat.col_num} / ${seat.row_num + 1} / ${seat.col_num + 1}`
                              }}
                            >
                              <SeatIcon
                                type={seat.seat_type}
                                color={seat.seat_color || '#2196F3'}
                                status={!seat.is_available ? 'sold' : selectedSeats.find(s => s.seat_code === seat.seat_code) ? 'selected' : 'available'}
                                onClick={() => handleSeatClick(seat)}
                              />
                            </div>
                          ))}
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          )}

          {step === 2 && (
            <div className="space-y-8 animate-in slide-in-from-bottom-4 duration-500 sm:space-y-10">
              <section className="space-y-5 sm:space-y-6">
                <h3 className="flex items-center gap-3 font-display text-lg font-black text-slate-900 sm:text-xl">
                  <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <i className="fas fa-map-marker-alt"></i>
                  </div>
                  Chọn điểm đón
                </h3>
                <div className="grid max-h-[500px] gap-3 overflow-auto pr-1 scrollbar-thin sm:gap-4 sm:pr-2">
                  {/* Combine regular and transfer points */}
                  {[...(data.pickup_points || []), ...(data.transfer_points || [])].map((point, idx) => {
                    const disabled = isPointDisabled(point);
                    const pointSurcharge = calculatePointSurcharge(point, selectedSeats.length);
                    
                    return (
                      <div key={idx} className="space-y-3">
                        <label 
                          className={`group flex cursor-pointer items-start gap-3 rounded-2xl border-2 p-4 transition-all sm:gap-4 sm:p-5 ${
                            disabled ? 'opacity-40 cursor-not-allowed bg-slate-50' :
                            selectedPickup?.id === point.id ? 'border-primary bg-primary/5' : 'border-slate-50 bg-white hover:border-primary-light'
                          }`}
                        >
                          <input 
                            type="radio" 
                            name="pickup" 
                            disabled={disabled}
                            className="mt-1 h-5 w-5 shrink-0 text-primary focus:ring-primary disabled:opacity-0"
                            checked={selectedPickup?.id === point.id}
                            onChange={() => setSelectedPickup(point)}
                          />
                          <div className="min-w-0 flex-1">
                            <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                              <span className="font-display text-lg font-black text-slate-900">{point.real_time}</span>
                              <div className="flex flex-wrap gap-1 sm:flex-col sm:items-end">
                                {pointSurcharge > 0 && (
                                  <span className="rounded-lg bg-warning/10 px-2 py-1 text-[10px] font-black text-warning">
                                    +{pointSurcharge.toLocaleString()}đ {point.surcharge_type == 1 ? '(Thanh toán sau)' : '(Cùng tiền vé)'}
                                  </span>
                                )}
                                {point.is_transfer && (
                                  <span className="rounded-lg bg-primary/10 px-2 py-1 text-[10px] font-black text-primary">
                                    <i className="fas fa-car-side mr-1"></i> Trung chuyển
                                  </span>
                                )}
                              </div>
                            </div>
                            <div className="mt-1 font-bold text-slate-700">{point.name}</div>
                            <div className="mt-1 text-xs text-slate-400">{point.address}</div>
                            {point.min_customer > selectedSeats.length && (
                              <div className="mt-2 text-[10px] font-bold text-danger italic">
                                * Cần đặt tối thiểu {point.min_customer} ghế để chọn điểm này
                              </div>
                            )}
                          </div>
                        </label>
                        {selectedPickup?.id === point.id && point.unfixed_point == 1 && (
                          <div className="animate-in slide-in-from-top-2 duration-300 sm:ml-9">
                            <input 
                              type="text"
                              value={pickupAddress}
                              onChange={(e) => setPickupAddress(e.target.value)}
                              placeholder="Nhập địa chỉ đón cụ thể..."
                              className="w-full rounded-2xl border-2 border-primary/20 bg-white px-5 py-3 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10"
                              required
                            />
                          </div>
                        )}
                      </div>
                    );
                  })}
                </div>
              </section>

              <section className="space-y-5 sm:space-y-6">
                <h3 className="flex items-center gap-3 font-display text-lg font-black text-slate-900 sm:text-xl">
                  <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-danger/10 text-danger">
                    <i className="fas fa-map-pin"></i>
                  </div>
                  Chọn điểm trả
                </h3>
                <div className="grid max-h-[500px] gap-3 overflow-auto pr-1 scrollbar-thin sm:gap-4 sm:pr-2">
                  {[...(data.drop_off_points_at_arrive || []), ...(data.transfer_points_at_arrive || [])].map((point, idx) => {
                    const disabled = isPointDisabled(point);
                    const pointSurcharge = calculatePointSurcharge(point, selectedSeats.length);

                    return (
                      <div key={idx} className="space-y-3">
                        <label 
                          className={`group flex cursor-pointer items-start gap-3 rounded-2xl border-2 p-4 transition-all sm:gap-4 sm:p-5 ${
                            disabled ? 'opacity-40 cursor-not-allowed bg-slate-50' :
                            selectedDropoff?.id === point.id ? 'border-primary bg-primary/5' : 'border-slate-50 bg-white hover:border-primary-light'
                          }`}
                        >
                          <input 
                            type="radio" 
                            name="dropoff" 
                            disabled={disabled}
                            className="mt-1 h-5 w-5 shrink-0 text-primary focus:ring-primary disabled:opacity-0"
                            checked={selectedDropoff?.id === point.id}
                            onChange={() => setSelectedDropoff(point)}
                          />
                          <div className="min-w-0 flex-1">
                            <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                              <span className="font-display text-lg font-black text-slate-900">{point.real_time}</span>
                              <div className="flex flex-wrap gap-1 sm:flex-col sm:items-end">
                                {pointSurcharge > 0 && (
                                  <span className="rounded-lg bg-warning/10 px-2 py-1 text-[10px] font-black text-warning">
                                    +{pointSurcharge.toLocaleString()}đ {point.surcharge_type == 1 ? '(Thanh toán sau)' : '(Cùng tiền vé)'}
                                  </span>
                                )}
                                {point.is_transfer && (
                                  <span className="rounded-lg bg-primary/10 px-2 py-1 text-[10px] font-black text-primary">
                                    <i className="fas fa-car-side mr-1"></i> Trung chuyển
                                  </span>
                                )}
                              </div>
                            </div>
                            <div className="mt-1 font-bold text-slate-700">{point.name}</div>
                            <div className="mt-1 text-xs text-slate-400">{point.address}</div>
                            {point.min_customer > selectedSeats.length && (
                              <div className="mt-2 text-[10px] font-bold text-danger italic">
                                * Cần đặt tối thiểu {point.min_customer} ghế để chọn điểm này
                              </div>
                            )}
                          </div>
                        </label>
                        {selectedDropoff?.id === point.id && point.unfixed_point == 1 && (
                          <div className="animate-in slide-in-from-top-2 duration-300 sm:ml-9">
                            <input 
                              type="text"
                              value={dropoffAddress}
                              onChange={(e) => setDropoffAddress(e.target.value)}
                              placeholder="Nhập địa chỉ trả cụ thể..."
                              className="w-full rounded-2xl border-2 border-primary/20 bg-white px-5 py-3 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10"
                              required
                            />
                          </div>
                        )}
                      </div>
                    );
                  })}
                </div>
              </section>
            </div>
          )}
        </div>

        {/* Sidebar Summary */}
        <aside className="space-y-6">
          <div className="overflow-hidden rounded-[20px] border border-slate-100 bg-white shadow-premium lg:sticky lg:top-24 lg:rounded-[2rem]">
            <div className="bg-slate-50/80 p-5 text-center sm:p-6">
              <h4 className="font-display text-xs font-black uppercase tracking-widest text-slate-400 sm:text-sm">Tổng tiền thanh toán</h4>
              <div className="mt-1 font-display text-3xl font-black text-primary-dark">
                {totalPrice.toLocaleString()}đ
              </div>
            </div>
            
            <div className="space-y-6 p-5 sm:p-8">
              <div className="space-y-4">
                <div className="flex items-start gap-3 border-b border-slate-50 pb-4">
                  <div className="mt-1 h-2 w-2 shrink-0 rounded-full bg-primary"></div>
                  <div className="flex-1">
                    <span className="text-xs font-bold text-slate-400 uppercase tracking-tighter">Số ghế:</span>
                    <div className="mt-0.5 text-sm font-black text-slate-900">
                      {selectedSeats.length > 0 ? selectedSeats.map(s => s.seat_code).join(', ') : 'Chưa chọn'}
                    </div>
                  </div>
                </div>
                
                {selectedPickup && (
                  <div className="flex items-start gap-3 border-b border-slate-50 pb-4">
                    <div className="mt-1 h-2 w-2 shrink-0 rounded-full bg-success"></div>
                    <div className="flex-1">
                      <span className="text-xs font-bold text-slate-400 uppercase tracking-tighter">Điểm đón:</span>
                      <div className="mt-0.5 text-sm font-black text-slate-900 line-clamp-2">
                        {selectedPickup.name}
                      </div>
                      {currentPickupSurcharge > 0 && (
                        <div className={`mt-1 text-[10px] font-bold ${selectedPickup.surcharge_type == 2 ? 'text-primary' : 'text-warning'}`}>
                          Phụ thu: {currentPickupSurcharge.toLocaleString()}đ {selectedPickup.surcharge_type == 1 && '(Trả sau)'}
                        </div>
                      )}
                    </div>
                  </div>
                )}

                {selectedDropoff && (
                  <div className="flex items-start gap-3">
                    <div className="mt-1 h-2 w-2 shrink-0 rounded-full bg-danger"></div>
                    <div className="flex-1">
                      <span className="text-xs font-bold text-slate-400 uppercase tracking-tighter">Điểm trả:</span>
                      <div className="mt-0.5 text-sm font-black text-slate-900 line-clamp-2">
                        {selectedDropoff.name}
                      </div>
                      {currentDropoffSurcharge > 0 && (
                        <div className={`mt-1 text-[10px] font-bold ${selectedDropoff.surcharge_type == 2 ? 'text-primary' : 'text-warning'}`}>
                          Phụ thu: {currentDropoffSurcharge.toLocaleString()}đ {selectedDropoff.surcharge_type == 1 && '(Trả sau)'}
                        </div>
                      )}
                    </div>
                  </div>
                )}
              </div>

              <div className="pt-6">
                {step === 1 && (
                  <button 
                    disabled={selectedSeats.length === 0}
                    onClick={() => setStep(2)}
                    className="w-full rounded-2xl bg-primary py-4 text-sm font-black text-white shadow-xl shadow-primary/20 transition-all hover:bg-primary-dark active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                  >
                    TIẾP TỤC
                  </button>
                )}
                {step === 2 && (
                  <div className="grid grid-cols-2 gap-3">
                    <button onClick={() => setStep(1)} className="rounded-2xl border-2 border-slate-100 py-4 text-xs font-black text-slate-500 hover:bg-slate-50">QUAY LẠI</button>
                    <button 
                      disabled={!selectedPickup || !selectedDropoff || isFinalizing}
                      onClick={handleFinalize}
                      className="rounded-2xl bg-primary py-4 text-xs font-black text-white shadow-lg shadow-primary/20 hover:bg-primary-dark disabled:opacity-50"
                    >
                      {isFinalizing ? <i className="fas fa-spinner animate-spin text-sm"></i> : 'XÁC NHẬN'}
                    </button>
                  </div>
                )}
              </div>
            </div>
          </div>
          
          <div className="rounded-2xl border border-warning/10 bg-warning/5 p-4 sm:p-6">
            <h5 className="flex items-center gap-2 text-xs font-black text-warning uppercase">
              <i className="fas fa-info-circle"></i> Lưu ý:
            </h5>
            <p className="mt-2 text-[10px] font-medium leading-relaxed text-warning-dark">
              Giá vé có thể thay đổi tùy theo số lượng khách và điểm đón trả bạn chọn. Các khoản phụ thu "Thanh toán sau" sẽ được thu trực tiếp bởi nhà xe khi bạn lên xe.
            </p>
          </div>
        </aside>
      </div>
    </div>
  );
};

export default SeatSelection;
