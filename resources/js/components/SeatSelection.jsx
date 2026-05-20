import React, { useState, useEffect, useMemo } from 'react';
import { createPortal } from 'react-dom';

const SeatIcon = ({ type, color, status, onClick, price, code, seatGroups }) => {
  const isSelected = status === 'selected';
  const isSold = status === 'sold';
  const fillColor = isSelected ? '#D1FAE5' : isSold ? '#CBD5E1' : '#FFFFFF';
  const strokeColor = isSelected ? '#10B981' : isSold ? '#CBD5E1' : color;
  const detailFill = isSelected ? '#10B981' : isSold ? '#CBD5E1' : '#F8FAFC';
  const detailStroke = isSelected ? '#10B981' : isSold ? '#CBD5E1' : '#CBD5E1';
  const opacity = status === 'sold' ? '0.8' : '1';

  let priceLabel = '';
  if (status !== 'sold') {
    if (seatGroups && seatGroups.length > 1) {
      const fares = seatGroups.map(g => Number(g.fare || 0));
      const minFare = Math.min(...fares);
      const maxFare = Math.max(...fares);
      if (minFare === maxFare) {
        priceLabel = `${minFare.toLocaleString()}đ`;
      } else {
        priceLabel = `${minFare.toLocaleString()}đ - ${maxFare.toLocaleString()}đ`;
      }
    } else if (price) {
      priceLabel = `${Number(price).toLocaleString()}đ`;
    }
  }

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
      className={`relative group/seat cursor-pointer transition-transform hover:scale-105 ${status === 'sold' ? 'cursor-not-allowed opacity-50' : ''}`}
      onClick={status !== 'sold' ? onClick : undefined}
      style={{ opacity }}
    >
      {renderPath()}
      {status === 'selected' && (
        <div className="absolute inset-0 flex items-center justify-center">
          <div className="flex h-[12px] w-[12px] items-center justify-center rounded-full bg-[#10B981] text-[5px] mb-1 text-white shadow-sm">
            <i className="fas fa-check"></i>
          </div>
        </div>
      )}

      {/* Tooltip on Hover */}
      {code && (
        <div className="pointer-events-none absolute bottom-full left-1/2 z-50 mb-2.5 -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-3 py-1.5 shadow-xl opacity-0 scale-90 origin-bottom transition-all duration-200 group-hover/seat:opacity-100 group-hover/seat:scale-100 flex flex-col items-center gap-0.5">
          <div className="text-[10px] font-semibold tracking-wider text-slate-300 uppercase leading-none mb-1">
            {code}
          </div>
          <div className="text-[11px] font-black text-white leading-none">
            {status === 'sold' ? 'Đã bán' : priceLabel || 'Liên hệ'}
          </div>
          {/* Arrow */}
          <div className="absolute -bottom-1 left-1/2 h-2 w-2 -translate-x-1/2 rotate-45 bg-slate-900"></div>
        </div>
      )}
    </div>
  );
};

const SeatSelection = ({ trip, onCancel, onComplete, legIndex = 0 }) => {
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

  const partnerId = trip.partner?.partner_id || trip.partner_id || 'vexere';

  const pointText = (value) => (value === undefined || value === null ? '' : String(value).trim());
  const isMeaningfulPointKey = (value) => {
    const text = pointText(value).toLowerCase();
    return text !== '' && text !== '0' && text !== 'null' && text !== 'undefined';
  };

  const getPointName = (point) => pointText(point?.name || point?.pointName || point?.officeName);
  const getPointTime = (point) => pointText(point?.real_time || point?.realTime || point?.time);
  const getPointAddress = (point) => pointText(point?.address || point?.full_address || point?.fullAddress);

  const getPointRawIdentity = (point) => {
    if (!point) return '';

    const directKeys = [
      point.__selectionKey,
      point.point_id,
      point.pointId,
      point.id,
      point.officeId,
      point.code,
      point.value
    ];

    const directKey = directKeys.map(pointText).find(isMeaningfulPointKey);
    if (directKey) return directKey;

    const fallbackParts = [
      getPointName(point),
      getPointTime(point),
      getPointAddress(point),
      pointText(point.duration),
      pointText(point.__pointType || point._point_type || point.point_type || point.type)
    ].filter(Boolean);

    return fallbackParts.length > 0 ? fallbackParts.join('|') : '';
  };

  const buildPointKey = (point, scope, index) => {
    const rawIdentity = getPointRawIdentity(point);
    return `${scope}:${rawIdentity || `index-${index}`}`;
  };

  const isTrueFlag = (value) => value === true || value === 1 || value === '1';

  const decoratePoint = (point, scope, index) => {
    const transferPoint = scope.includes('transfer') || isTrueFlag(point?.is_transfer) || isTrueFlag(point?.isTransfer) || isTrueFlag(point?._is_transfer);

    return {
      ...point,
      __selectionKey: buildPointKey(point, scope, index),
      __pointType: scope,
      _point_type: scope,
      is_transfer: transferPoint,
      isTransfer: transferPoint,
      _is_transfer: transferPoint
    };
  };

  const isTransferPoint = (point, expectedScope = '') => {
    if (!point) return false;
    const pointType = pointText(point.__pointType || point._point_type || point.point_type);
    return Boolean(
      isTrueFlag(point.is_transfer) ||
      isTrueFlag(point.isTransfer) ||
      isTrueFlag(point._is_transfer) ||
      pointType.includes('transfer') ||
      (expectedScope && pointType === expectedScope)
    );
  };

  const getNumericTime = (point) => {
    if (!point) return null;
    if (point.duration !== undefined && point.duration !== null) {
      const num = Number(point.duration);
      if (!isNaN(num)) return num;
    }
    const val = point.real_time || point.realTime;
    if (!val || typeof val !== 'string') return null;
    const parts = val.split(' ');
    if (parts.length < 2) return null;
    const t = parts[0].split(':');
    const d = parts[1].split('-');
    if (t.length < 2 || d.length < 3) return null;
    const hh = ('0' + parseInt(t[0], 10)).slice(-2);
    const mm = ('0' + parseInt(t[1], 10)).slice(-2);
    const DD = ('0' + parseInt(d[0], 10)).slice(-2);
    const MM = ('0' + parseInt(d[1], 10)).slice(-2);
    const YYYY = d[2];
    const iso = `${YYYY}-${MM}-${DD}T${hh}:${mm}:00`;
    const ts = Date.parse(iso);
    return isNaN(ts) ? null : ts;
  };

  const isPointSelected = (selected, current) => {
    if (!selected || !current) return false;
    const selectedKey = getPointRawIdentity(selected);
    const currentKey = getPointRawIdentity(current);
    return selectedKey !== '' && currentKey !== '' && selectedKey === currentKey;
  };

  const pickupPoints = useMemo(() => {
    const regularPoints = (data?.pickup_points || []).map((point, index) => decoratePoint(point, 'pickup-point', index));
    const transferPoints = (data?.transfer_points || []).map((point, index) => decoratePoint(point, 'transfer-point', index));
    const points = [...regularPoints, ...transferPoints];

    if (partnerId === 'goopay') {
      return [...points].sort((a, b) => {
        const tA = getNumericTime(a) || 0;
        const tB = getNumericTime(b) || 0;
        return tA - tB;
      });
    }

    return points;
  }, [data, partnerId]);

  const allDropoffPoints = useMemo(() => {
    const regularPoints = (data?.drop_off_points_at_arrive || []).map((point, index) => decoratePoint(point, 'dropoff-point', index));
    const transferPoints = (data?.transfer_points_at_arrive || []).map((point, index) => decoratePoint(point, 'dropoff-transfer-point', index));
    const points = [...regularPoints, ...transferPoints];

    if (partnerId === 'goopay') {
      return [...points].sort((a, b) => {
        const tA = getNumericTime(a) || 0;
        const tB = getNumericTime(b) || 0;
        return tA - tB;
      });
    }

    return points;
  }, [data, partnerId]);

  const visibleDropoffPoints = useMemo(() => {
    if (partnerId !== 'goopay' || !selectedPickup) return allDropoffPoints;

    const pickupTime = getNumericTime(selectedPickup);
    let maxTs = -Infinity;
    let maxPoint = null;

    allDropoffPoints.forEach(point => {
      const ts = getNumericTime(point);
      if (ts !== null && ts > maxTs) {
        maxTs = ts;
        maxPoint = point;
      }
    });

    return allDropoffPoints.filter(point => {
      const ts = getNumericTime(point);
      if (maxPoint && isPointSelected(maxPoint, point)) return true;
      if (pickupTime !== null && ts !== null) return ts > pickupTime;
      return true;
    });
  }, [allDropoffPoints, partnerId, selectedPickup]);

  // Reset selected dropoff if it becomes invalid under new selected pickup (for Goopay)
  useEffect(() => {
    if (partnerId === 'goopay' && selectedPickup && selectedDropoff) {
      const pickupTime = getNumericTime(selectedPickup);
      const dropoffTime = getNumericTime(selectedDropoff);

      let maxTs = -Infinity;
      let maxPoint = null;
      allDropoffPoints.forEach(p => {
        const ts = getNumericTime(p);
        if (ts !== null && ts > maxTs) {
          maxTs = ts;
          maxPoint = p;
        }
      });

      if (pickupTime !== null && dropoffTime !== null) {
        const isEnd = maxPoint && isPointSelected(maxPoint, selectedDropoff);
        if (dropoffTime <= pickupTime && !isEnd) {
          setSelectedDropoff(null);
        }
      }
    }
  }, [selectedPickup, selectedDropoff, partnerId, allDropoffPoints]);

  useEffect(() => {
    const formData = new FormData();
    formData.append('action', 'choose_trip_ajax_booking');
    formData.append('partnerId', trip.partner?.partner_id || trip.partner_id || 'vexere');
    formData.append('tripCode', trip.trip_id);
    const timeOnly = trip.departure_time || (trip.pickup_date ? (trip.pickup_date.includes('T') ? trip.pickup_date.split('T')[1]?.slice(0, 5) : (trip.pickup_date.includes(' ') ? trip.pickup_date.split(' ')[1]?.slice(0, 5) : '')) : '');
    formData.append('departureTime', timeOnly || '00:00');
    formData.append('wayId', trip.way_id || trip.wayId || '');
    formData.append('bookingId', trip.booking_id || trip.bookingId || '');
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
    let tiers = [];

    if (point.surcharge_tiers) {
      try {
        tiers = typeof point.surcharge_tiers === 'string' ? JSON.parse(point.surcharge_tiers) : point.surcharge_tiers;
      } catch (error) {
        tiers = [];
      }
    }

    if (!Array.isArray(tiers)) tiers = [];

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

    const seatTotal = selectedSeats.reduce((sum, seat) => sum + Number(seat.fare || 0), 0);
    const pickupPayNowSurcharge = selectedPickup?.surcharge_type == 2 ? calculatePointSurcharge(selectedPickup, selectedSeats.length) : 0;
    const dropoffPayNowSurcharge = selectedDropoff?.surcharge_type == 2 ? calculatePointSurcharge(selectedDropoff, selectedSeats.length) : 0;
    const pickupDate = pointText(trip.pickup_date);
    const departureDate = data.departure_date || trip.departure_date || (pickupDate ? pickupDate.split('T')[0].split(' ')[0] : '');
    const departureTime = data.departure_time || trip.departure_time || (pickupDate ? (pickupDate.includes('T') ? pickupDate.split('T')[1]?.slice(0, 5) : (pickupDate.includes(' ') ? pickupDate.split(' ')[1]?.slice(0, 5) : '')) : '');

    // Only keep booking-critical info from data to reduce payload size
    const essentialData = {
      company_logo: data.company_logo || trip.company_logo || '',
      company_name: data.company_name || trip.company_name || '',
      name: data.name || trip.vehicle_type || trip.name || '',
      trip_code: data.trip_code || trip.trip_code || trip.trip_id || '',
      departure_date: departureDate,
      departure_time: departureTime,
      routeName: data.routeName || data.route_name || trip.route_name || trip.routeName || trip.name || ''
    };

    const isPickupTransfer = isTransferPoint(selectedPickup, 'transfer-point');
    const isDropoffTransfer = isTransferPoint(selectedDropoff, 'dropoff-transfer-point');

    const ticket = {
      tripId: trip.trip_id,
      partnerId: trip.partner?.partner_id || trip.partner_id || 'vexere',
      selectedSeats: selectedSeats.map(s => {
        const seatCode = s.seat_code || s.code || s.seatCode || s.full_code || s.fullCode || s.id || s.seat_id;
        const fullCode = s.full_code || s.fullCode || s.id || s.seat_id || seatCode;
        const seatGroupCode = s.seat_group_code || s.group || '';

        return {
          id: s.id || s.seat_id || fullCode,
          seat_id: s.seat_id || s.id || fullCode,
          seat_code: seatCode,
          seatCode,
          code: seatCode,
          full_code: fullCode,
          full_code_group: s.full_code_group,
          fare: Number(s.fare || 0),
          group: seatGroupCode, // Compatibility
          seat_group_code: seatGroupCode
        };
      }),
      pickupPoint: isPickupTransfer ? null : selectedPickup,
      transferPickupPoint: isPickupTransfer ? selectedPickup : null,
      dropoffPoint: isDropoffTransfer ? null : selectedDropoff,
      transferDropoffPoint: isDropoffTransfer ? selectedDropoff : null,
      pickupPointMoreDesc: pickupAddress,
      dropoffPointMoreDesc: dropoffAddress,
      pickupSurcharge: pickupPayNowSurcharge,
      dropoffSurcharge: dropoffPayNowSurcharge,
      departure_date: departureDate,
      departure_time: departureTime,
      seatsAndInfoData: essentialData,
      wayId: trip.way_id || trip.wayId || '',
      bookingId: trip.booking_id || trip.bookingId || '',
      routeName: trip.route_name || trip.routeName || trip.name || '',
      subtotalSeats: seatTotal,
      subtotal: seatTotal + pickupPayNowSurcharge + dropoffPayNowSurcharge
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
      formData.append('legIndex', String(legIndex));

      formData.append('ticket[tripId]', ticket.tripId);
      formData.append('ticket[partnerId]', ticket.partnerId);
      formData.append('ticket[pickupPoint]', ticket.pickupPoint ? JSON.stringify(ticket.pickupPoint) : '');
      formData.append('ticket[transferPickupPoint]', ticket.transferPickupPoint ? JSON.stringify(ticket.transferPickupPoint) : '');
      formData.append('ticket[dropoffPoint]', ticket.dropoffPoint ? JSON.stringify(ticket.dropoffPoint) : '');
      formData.append('ticket[transferDropoffPoint]', ticket.transferDropoffPoint ? JSON.stringify(ticket.transferDropoffPoint) : '');
      formData.append('ticket[selectedSeats]', JSON.stringify(ticket.selectedSeats));
      formData.append('ticket[seatsAndInfoData]', JSON.stringify(ticket.seatsAndInfoData));
      formData.append('ticket[pickupPointMoreDesc]', ticket.pickupPointMoreDesc || '');
      formData.append('ticket[dropoffPointMoreDesc]', ticket.dropoffPointMoreDesc || '');
      formData.append('ticket[pickupSurcharge]', ticket.pickupSurcharge);
      formData.append('ticket[dropoffSurcharge]', ticket.dropoffSurcharge);
      formData.append('ticket[departure_date]', ticket.departure_date || '');
      formData.append('ticket[departure_time]', ticket.departure_time || '');
      formData.append('ticket[wayId]', ticket.wayId || '');
      formData.append('ticket[bookingId]', ticket.bookingId || '');
      formData.append('ticket[routeName]', ticket.routeName || '');
      formData.append('ticket[subtotalSeats]', ticket.subtotalSeats || 0);
      formData.append('ticket[subtotal]', ticket.subtotal || 0);

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
    const maxSeats = partnerId === 'goopay' ? 5 : 8;

    setSelectedSeats(prev => {
      const isSelected = prev.some(s => {
        if (s.full_code && seat.full_code) return String(s.full_code) === String(seat.full_code);
        return String(s.seat_code) === String(seat.seat_code);
      });

      if (isSelected) {
        return prev.filter(s => {
          if (s.full_code && seat.full_code) return String(s.full_code) !== String(seat.full_code);
          return String(s.seat_code) !== String(seat.seat_code);
        });
      }

      if (prev.length >= maxSeats) {
        alert(`Bạn được chọn tối đa ${maxSeats} chỗ cho mỗi lần đặt`);
        return prev;
      }

      const newSeat = { ...seat };
      if (group) {
        newSeat.fare = group.fare;
        newSeat.seat_group_code = group.seat_group_id || group.seat_group_code;
        newSeat.seat_group = group.seat_group;
        if (newSeat.full_code && newSeat.seat_group_code) {
          newSeat.full_code_group = `${newSeat.full_code}|${newSeat.seat_group_code}`;
        }
      }
      return [...prev, newSeat];
    });
    setSeatGroupSelector(null);
  };

  const handleSeatClick = (seat) => {
    const isSelected = selectedSeats.some(s => {
      if (s.full_code && seat.full_code) return String(s.full_code) === String(seat.full_code);
      return String(s.seat_code) === String(seat.seat_code);
    });

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
    const seatTotal = selectedSeats.reduce((sum, s) => sum + Number(s.fare || 0), 0);
    // Only add surcharge to total if it's "Pay Now" (type 2)
    const pSurcharge = (selectedPickup?.surcharge_type == 2) ? currentPickupSurcharge : 0;
    const dSurcharge = (selectedDropoff?.surcharge_type == 2) ? currentDropoffSurcharge : 0;
    return seatTotal + pSurcharge + dSurcharge;
  }, [selectedSeats, currentPickupSurcharge, currentDropoffSurcharge, selectedPickup, selectedDropoff]);

  if (loading) {
    return (
      <div className="flex items-center justify-center py-20">
        <div className="h-12 w-12 premium-spinner"></div>
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
    <div className="dailyve-seat-selection animate-in fade-in slide-in-from-top-4 duration-500">
      {/* Seat Group Selector Modal */}
      {seatGroupSelector && createPortal(
        <div className="fixed inset-0 z-[999] flex items-center justify-center p-4">
          <div 
            className="absolute inset-0 bg-[#0F172A]/60 transition-all duration-300 animate-in fade-in"
            onClick={() => setSeatGroupSelector(null)}
          ></div>
          
          <div className="relative w-full max-w-md overflow-hidden rounded-2xl bg-white border border-[#E2E8F0] shadow-2xl animate-in zoom-in-95 duration-300 flex flex-col">
            <div className="relative bg-[#2196F3] p-6 text-center text-white shrink-0 shadow-sm">
              <button 
                onClick={() => setSeatGroupSelector(null)}
                className="absolute top-4 right-4 flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-white/90 hover:bg-white/20 transition-all active:scale-95"
              >
                <i className="fas fa-times text-sm"></i>
              </button>
              <h3 className="font-display text-xl font-semibold tracking-tight leading-tight">Chọn loại vé</h3>
              <p className="mt-1 text-xs text-white/90 font-medium">
                Giường <span className="font-semibold bg-white/20 px-2 py-0.5 rounded-md">{seatGroupSelector.seat.seat_code}</span> có nhiều lựa chọn giá
              </p>
            </div>

            {/* Options list in body */}
            <div className="p-6 space-y-3 max-h-[50vh] overflow-y-auto pr-2 scrollbar-thin">
              {seatGroupSelector.groups.map((group, idx) => {
                const originalPrice = group.fares?.original || group.originalPrice || group.fares?.original_fare || group.original_fare;
                const hasDiscount = originalPrice && originalPrice > group.fare;
                const discountPercent = hasDiscount ? Math.round(((originalPrice - group.fare) / originalPrice) * 100) : 0;
                
                return (
                  <button
                    key={idx}
                    onClick={() => toggleSeat(seatGroupSelector.seat, group)}
                    className="group relative flex w-full items-center justify-between rounded-xl border border-[#E2E8F0] bg-white p-4 transition-all duration-200 hover:bg-[#0F172A] hover:border-[#0F172A] hover:shadow-md active:scale-[0.98]"
                  >
                    {/* Option Details */}
                    <div className="flex items-center gap-3 text-left">
                      <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#F1F5F9] text-[#2196F3] group-hover:bg-white/10 group-hover:text-white transition-colors">
                        <i className="fas fa-chair text-sm"></i>
                      </div>
                      <div className="min-w-0">
                        <div className="font-semibold text-slate-800 group-hover:text-white transition-colors text-sm tracking-tight leading-snug">
                          {group.seat_group}
                        </div>
                        <div className="text-[11px] font-medium text-slate-400 group-hover:text-slate-400 mt-0.5">
                          Giá áp dụng cho loại này
                        </div>
                      </div>
                    </div>

                    {/* Pricing details */}
                    <div className="text-right">
                      {hasDiscount && (
                        <div className="flex items-center justify-end gap-1.5 mb-0.5">
                          <span className="text-[10px] font-bold text-slate-400 line-through group-hover:text-slate-400">
                            {originalPrice.toLocaleString()}đ
                          </span>
                          <span className="rounded bg-[#FB923C]/15 px-1.5 py-0.5 text-[9px] font-black text-[#FB923C] group-hover:bg-[#FB923C] group-hover:text-white transition-colors">
                            -{discountPercent}%
                          </span>
                        </div>
                      )}
                      <div className="font-display text-base font-semibold text-[#2196F3] group-hover:text-white transition-colors">
                        {(group.fare || 0).toLocaleString()}đ
                      </div>
                    </div>
                  </button>
                );
              })}
            </div>

            {/* Modal Footer with subtle close text button: h-10 standard button-secondary */}
            <div className="px-6 pb-6 pt-2 border-t border-[#F1F5F9] bg-[#F8FAFC] flex justify-center shrink-0">
              <button
                onClick={() => setSeatGroupSelector(null)}
                className="w-full rounded-lg border border-[#E2E8F0] bg-white py-2.5 text-xs font-semibold uppercase tracking-wider text-slate-500 transition-all hover:bg-[#F8FAFC] hover:text-[#0F172A] active:scale-95 duration-150"
              >
                HỦY BỎ
              </button>
            </div>
          </div>
        </div>,
        document.body
      )}

      {/* Steps Header */}
      <div className="mb-6 flex items-center justify-between gap-3 border-b border-slate-100 pb-5 sm:mb-8 sm:pb-6">
        <div className="flex min-w-0 flex-wrap items-center gap-3 sm:gap-8">
          {[1, 2].map((s) => (
            <div
              key={s}
              className={`flex items-center gap-3 transition-all ${step === s ? 'opacity-100' : 'opacity-40'}`}
            >
              <div className={`flex h-10 w-10 items-center justify-center rounded-full font-black ${step === s ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-slate-200 text-slate-500'
                }`}>
                {s}
              </div>
              <span className="hidden text-sm font-black uppercase tracking-widest sm:block">
                {s === 1 ? 'Chọn ghế' : 'Điểm đón trả'}
              </span>
            </div>
          ))}
        </div>
        <button onClick={onCancel} className="text-slate-400! hover:text-danger! bg-white! transition-colors">
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
                    <SeatIcon type={data.coach_seat_template[0]?.seats[0]?.seat_type || 2} color="#10B981" status="selected" />
                  </div>
                  <div className="flex flex-col">
                    <span className="text-xs font-black text-emerald-600 uppercase tracking-wider text-emerald-600">Đang chọn</span>
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
                          name: seat.seat_group || 'Ghế còn trống',
                          color: seat.seat_color || '#2196F3',
                          type: seat.seat_type,
                          fare: seat.fare,
                          originalFare: seat.fares?.original || seat.fares?.original_fare || seat.original_fare || seat.originalPrice
                        };
                      }
                    });
                  });
                  return Object.values(groups).map((group, idx) => {
                    const hasDiscount = group.originalFare && group.originalFare > group.fare;
                    return (
                      <div key={idx} className="flex items-center gap-4">
                        <SeatIcon type={group.type} color={group.color} status="available" />
                        <div className="flex flex-col">
                          <span className="text-xs font-bold text-slate-800 uppercase tracking-wider">{group.name}</span>
                          <div className="flex items-center gap-1.5 mt-0.5">
                            {hasDiscount && (
                              <span className="text-[10px] font-bold text-slate-400 line-through">
                                {(group.originalFare).toLocaleString()}đ
                              </span>
                            )}
                            <span className="font-display text-[11px] font-black text-primary">{(group.fare || 0).toLocaleString()}đ</span>
                          </div>
                        </div>
                      </div>
                    );
                  });
                })()}
              </div>

              <div className="flex flex-col items-center gap-8 py-4 sm:py-8 md:flex-row md:items-start md:justify-center">
                {data.coach_seat_template.map((coach, idx) => {
                  const maxGridWidth = coach.num_cols > 4 ? 'w-full max-w-[320px] sm:max-w-[360px]' : 'w-full max-w-[260px] sm:max-w-[280px]';
                  return (
                    <div key={idx} className={`${maxGridWidth} space-y-4`}>
                      <h4 className="text-center font-display text-sm font-black uppercase tracking-widest text-slate-400">{coach.coach_name}</h4>
                      <div className="w-full">
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
                                status={!seat.is_available ? 'sold' : selectedSeats.some(s => {
                                  if (s.full_code && seat.full_code) return String(s.full_code) === String(seat.full_code);
                                  return String(s.seat_code) === String(seat.seat_code);
                                }) ? 'selected' : 'available'}
                                onClick={() => handleSeatClick(seat)}
                                price={seat.fare}
                                code={seat.seat_code}
                                seatGroups={seat.seat_groups}
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
                  {pickupPoints.map((point, idx) => {
                    const disabled = isPointDisabled(point);
                    const pointSurcharge = calculatePointSurcharge(point, selectedSeats.length);

                    return (
                      <div key={point.__selectionKey || idx} className="space-y-3">
                        <label
                          className={`group flex cursor-pointer items-start gap-3 rounded-2xl border-2 p-4 transition-all sm:gap-4 sm:p-5 ${disabled ? 'opacity-40 cursor-not-allowed bg-slate-50' :
                              isPointSelected(selectedPickup, point) ? 'border-primary bg-primary/5' : 'border-slate-50 bg-white hover:border-primary-light'
                            }`}
                        >
                          <input
                            type="radio"
                            name="pickup"
                            disabled={disabled}
                            className="mt-1 h-5 w-5 shrink-0 text-primary focus:ring-primary disabled:opacity-0"
                            checked={isPointSelected(selectedPickup, point)}
                            onChange={() => {
                              setSelectedPickup(point);
                              setPickupAddress('');
                            }}
                          />
                          <div className="min-w-0 flex-1">
                            <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                              <span className="font-display text-lg font-black text-slate-900">{getPointTime(point)}</span>
                              <div className="flex flex-wrap gap-1 sm:flex-col sm:items-end">
                                {pointSurcharge > 0 && (
                                  <span className="rounded-lg bg-warning/10 px-2 py-1 text-[10px] font-black text-warning">
                                    +{pointSurcharge.toLocaleString()}đ {point.surcharge_type == 1 ? '(Thanh toán sau)' : '(Cùng tiền vé)'}
                                  </span>
                                )}
                                {isTransferPoint(point) && (
                                  <span className="rounded-lg bg-primary/10 px-2 py-1 text-[10px] font-black text-primary">
                                    <i className="fas fa-car-side mr-1"></i> Trung chuyển
                                  </span>
                                )}
                              </div>
                            </div>
                            <div className="mt-1 font-bold text-slate-700">{getPointName(point)}</div>
                            <div className="mt-1 text-xs text-slate-400">{getPointAddress(point)}</div>
                            {point.min_customer > selectedSeats.length && (
                              <div className="mt-2 text-[10px] font-bold text-danger italic">
                                * Cần đặt tối thiểu {point.min_customer} ghế để chọn điểm này
                              </div>
                            )}
                          </div>
                        </label>
                        {isPointSelected(selectedPickup, point) && point.unfixed_point == 1 && (
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
                  {visibleDropoffPoints.map((point, idx) => {
                    const disabled = isPointDisabled(point);
                    const pointSurcharge = calculatePointSurcharge(point, selectedSeats.length);

                    return (
                      <div key={point.__selectionKey || idx} className="space-y-3">
                        <label
                          className={`group flex cursor-pointer items-start gap-3 rounded-2xl border-2 p-4 transition-all sm:gap-4 sm:p-5 ${disabled ? 'opacity-40 cursor-not-allowed bg-slate-50' :
                              isPointSelected(selectedDropoff, point) ? 'border-primary bg-primary/5' : 'border-slate-50 bg-white hover:border-primary-light'
                            }`}
                        >
                          <input
                            type="radio"
                            name="dropoff"
                            disabled={disabled}
                            className="mt-1 h-5 w-5 shrink-0 text-primary focus:ring-primary disabled:opacity-0"
                            checked={isPointSelected(selectedDropoff, point)}
                            onChange={() => {
                              setSelectedDropoff(point);
                              setDropoffAddress('');
                            }}
                          />
                          <div className="min-w-0 flex-1">
                            <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                              <span className="font-display text-lg font-black text-slate-900">{getPointTime(point)}</span>
                              <div className="flex flex-wrap gap-1 sm:flex-col sm:items-end">
                                {pointSurcharge > 0 && (
                                  <span className="rounded-lg bg-warning/10 px-2 py-1 text-[10px] font-black text-warning">
                                    +{pointSurcharge.toLocaleString()}đ {point.surcharge_type == 1 ? '(Thanh toán sau)' : '(Cùng tiền vé)'}
                                  </span>
                                )}
                                {isTransferPoint(point) && (
                                  <span className="rounded-lg bg-primary/10 px-2 py-1 text-[10px] font-black text-primary">
                                    <i className="fas fa-car-side mr-1"></i> Trung chuyển
                                  </span>
                                )}
                              </div>
                            </div>
                            <div className="mt-1 font-bold text-slate-700">{getPointName(point)}</div>
                            <div className="mt-1 text-xs text-slate-400">{getPointAddress(point)}</div>
                            {point.min_customer > selectedSeats.length && (
                              <div className="mt-2 text-[10px] font-bold text-danger italic">
                                * Cần đặt tối thiểu {point.min_customer} ghế để chọn điểm này
                              </div>
                            )}
                          </div>
                        </label>
                        {isPointSelected(selectedDropoff, point) && point.unfixed_point == 1 && (
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
              <h4 className="font-display text-xs font-bold uppercase tracking-widest text-slate-400 sm:text-sm">Tổng tiền thanh toán</h4>
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
                      {selectedSeats.length > 0 ? selectedSeats.map(s => s.seat_code || s.code || s.seatCode).filter(Boolean).join(', ') : 'Chưa chọn'}
                    </div>
                  </div>
                </div>

                {selectedPickup && (
                  <div className="flex items-start gap-3 border-b border-slate-50 pb-4">
                    <div className="mt-1 h-2 w-2 shrink-0 rounded-full bg-success"></div>
                    <div className="flex-1">
                      <span className="text-xs font-bold text-slate-400 uppercase tracking-tighter">Điểm đón:</span>
                      <div className="mt-0.5 text-sm font-black text-slate-900 line-clamp-2">
                        {getPointName(selectedPickup)}
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
                        {getPointName(selectedDropoff)}
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
