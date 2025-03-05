document.addEventListener("DOMContentLoaded", function () {
    const services = document.querySelectorAll(".service");
    const vehicleType = document.getElementById("vehicleType");
    const totalAmount = document.getElementById("totalAmount");
    const totalPriceInput = document.getElementById("totalPrice");
    const schedule = document.getElementById("schedule");
    const timeSlot = document.getElementById("timeSlot");

    // Function to calculate the total price based on selected services and vehicle type
    function calculateTotalPrice() {
        let total = 0;
        const selectedVehicleType = vehicleType.value;

        services.forEach(service => {
            if (service.checked) {
                const price = service.getAttribute(`data-price-${selectedVehicleType}`);
                total += parseInt(price);
            }
        });

        totalAmount.textContent = total;
        totalPriceInput.value = total;
    }

     function checkTimeSlotAvailability(selectedDate) {
    // Make sure the selected date is passed correctly in the URL
    if (selectedDate) {
        fetch(`check_time_slot_availability.php?date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                console.log(data);

                if (data && data.slots) {
                    // Loop through each time slot
                    for (const slot in data.slots) {
                        const slotId = `slot-${slot.replace(/ /g, '-').toLowerCase()}`; // Create the slot ID based on the slot value
                        const slotElement = document.getElementById(slotId);

                        if (slotElement) {
                            if (data.slots[slot]) {
                                // If the slot is booked (true), color it red
                                slotElement.style.backgroundColor = 'red';
                                slotElement.disabled = true; // Optionally disable the booked slot
                            } else {
                                // If the slot is available (false), color it green or leave it as default
                                slotElement.style.backgroundColor = 'green';
                                slotElement.disabled = false; // Make sure available slots are enabled
                            }
                        }
                    }
                } else {
                    console.error("Error: Invalid slot data received.");
                }
            })
            .catch(error => console.error("Error checking time slot availability:", error));
    } else {
        console.error("No date selected.");
    }
}
  // Attach event listeners
    vehicleType.addEventListener("change", calculateTotalPrice);
    services.forEach(service => service.addEventListener("change", calculateTotalPrice));

    schedule.addEventListener("change", function () {
        const selectedDate = schedule.value;

        fetch(`check_booking_availability.php?date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.bookings >= 5) {
                    alert("This date is fully booked. Please select another date.");
                    schedule.value = '';  // Clear the selected date
                    schedule.style.backgroundColor = 'red'; // Mark the date as fully booked
                } else {
                    if (data.bookings >= 3) {
                        schedule.style.backgroundColor = 'yellow';  // Show yellow for dates with 3-4 bookings
                    } else {
                        schedule.style.backgroundColor = 'green';  // Show green for dates with less than 3 bookings
                    }
                    // Check time slot availability after the date selection
                    checkTimeSlotAvailability(selectedDate);
                }
            })
            .catch(error => console.error("Error checking booking availability:", error));
    });

    timeSlot.addEventListener("change", function () {
        const selectedDate = schedule.value;
        if (selectedDate) {
            checkTimeSlotAvailability(selectedDate);
        }
    });

    // Initial calculation of the total price
    calculateTotalPrice();
});
