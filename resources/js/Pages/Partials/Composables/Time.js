export function FormatElapsedTime(seconds) {
    const hrs = Math.floor(seconds / 3600).toString().padStart(2, '0');
    const mins = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
    const secs = (seconds % 60).toString().padStart(2, '0');
    return `${hrs}:${mins}:${secs}`;
};

export function ParseDateTimeLocalToSeconds(dateTimeLocal) {
    // Parse the datetime-local string into a Date object
    const date = new Date(dateTimeLocal);
    // Convert the Date object to milliseconds since the Unix epoch and then to seconds
    return Math.floor(date.getTime() / 1000);
}

export function FormatDateTime(timestamp) {
    var date = new Date(timestamp);
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    var hours = String(date.getHours()).padStart(2, '0');
    var minutes = String(date.getMinutes()).padStart(2, '0');
    var seconds = String(date.getSeconds()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

// Function to convert date string or day number to day name or special cases (Today, Yesterday)
export function GetDayName(dateInput) {
    let date;
    
    // If it's a date string (YYYY-MM-DD format), parse it
    if (typeof dateInput === 'string' && dateInput.includes('-')) {
        date = new Date(dateInput + 'T00:00:00'); // Add time to avoid timezone issues
    } else {
        // Legacy support: if it's just a number, assume current month (for backward compatibility)
        date = new Date();
        date.setDate(dateInput);
    }

    // Get today's date and yesterday's date
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(today.getDate() - 1);

    // Check if the provided date is today
    if (date.getFullYear() === today.getFullYear() && 
        date.getMonth() === today.getMonth() && 
        date.getDate() === today.getDate()) {
        return "Today";
    }

    // Check if the provided date is yesterday
    if (date.getFullYear() === yesterday.getFullYear() && 
        date.getMonth() === yesterday.getMonth() && 
        date.getDate() === yesterday.getDate()) {
        return "Yesterday";
    }

    // Array of day names
    const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    // Get the day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
    const dayOfWeek = date.getDay();

    // Return the corresponding day name
    return days[dayOfWeek];
};

export function WeekRange( year, weekNumber, startDay = 1 ) {
    // startDay: 0 (Sunday), 1 (Monday), etc.
    const firstDayOfYear = new Date(year, 0, 1); // January 1st
    const dayOffset = (firstDayOfYear.getDay() + 7 - startDay) % 7; // Align to the startDay
    const weekStart = new Date(firstDayOfYear.getTime());
    weekStart.setDate(firstDayOfYear.getDate() + weekNumber * 7 - 7 - dayOffset);
    const weekEnd = new Date(weekStart.getTime());
    weekEnd.setDate(weekStart.getDate() + 6);

    return {
        start: weekStart,
        end: weekEnd,
    };
}