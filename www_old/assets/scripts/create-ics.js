function makeIcsFile(
  eventId,
  eventStart,
  eventEnd,
  eventTitle,
  eventDescription,
  eventLocation
) {
  let lf = "%0D%0A";
  let ics =
    "BEGIN:VCALENDAR" +
    lf +
    "CALSCALE:GREGORIAN" +
    lf +
    "METHOD:PUBLISH" +
    lf +
    "PRODID:-//Educachien Cal//FR" +
    lf +
    "VERSION:2.0" +
    lf +
    "BEGIN:VEVENT" +
    lf +
    "UID:" +
    eventId +
    lf +
    "DTSTART;TZID=Europe/Brussels:" +
    eventStart +
    lf +
    "DTSTAMP:" +
    eventStart +
    lf +
    "DTEND;TZID=Europe/Brussels:" + 
    eventEnd + 
    lf +
    "SUMMARY:" +
    eventTitle.replaceAll(',', '\\,') +
    lf +
    "DESCRIPTION:" +
    eventDescription.replaceAll(',', '\\,') +
    lf +
    "LOCATION:" +
    eventLocation.replaceAll(',', '\\,') +
    lf +
    "TZID:Europe/Brussels" +
    lf +
    "END:VEVENT" +
    lf +
    "END:VCALENDAR";
  return ics;
}

function createFile(
  eventId,
  eventStart,
  eventEnd,
  eventTitle,
  eventDescription,
  eventLocation
) {
 let link = document.querySelector("#calendar_link_" + eventId);
  link.href = "data:text/calendar;charset=utf-8," +  makeIcsFile(
    eventId,
    eventStart,
    eventEnd,
    eventTitle,
    eventDescription,
    eventLocation
  );
  link.download = eventId + ".ics";
}