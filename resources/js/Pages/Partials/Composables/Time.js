export function FormatElapsedTime(seconds) {
    const hrs = Math.floor(seconds / 3600).toString().padStart(2, '0');
    const mins = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
    const secs = (seconds % 60).toString().padStart(2, '0');
    return `${hrs}:${mins}:${secs}`;
};

// Function to convert numeric day to day name or special cases (Today, Yesterday)
export function GetDayName(day) {
    // Create a new Date object for the current month and year, and set the day
    const date = new Date();
    const currentYear = date.getFullYear();
    const currentMonth = date.getMonth();

    // Set the provided day
    date.setDate(day);

    // Get today's date and yesterday's date
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(today.getDate() - 1);

    // Check if the provided day is today
    if (date.getFullYear() === today.getFullYear() && date.getMonth() === today.getMonth() && date.getDate() === today.getDate()) {
        return "Today";
    }

    // Check if the provided day is yesterday
    if (date.getFullYear() === yesterday.getFullYear() && date.getMonth() === yesterday.getMonth() && date.getDate() === yesterday.getDate()) {
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