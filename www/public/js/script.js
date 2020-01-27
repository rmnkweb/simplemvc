$(document).ready(function () {
    $(".confirmDelete").on("click", function (e) {
        e.preventDefault();

        if (confirm("Вы уверены, что хотите удалить эту запись?")) {
            window.location.href = e.target.href;
        }
    });

    $(".datetimepicker").each(function () {
        $initialVal = $(this).val() ? $(this).val() : false;
        if ($initialVal) {
            $date = new Date($initialVal.replace(/-/g,"/"));
            var twoDigitMonth = $date.getMonth() + "";
            if (twoDigitMonth.length == 1)
                twoDigitMonth = "0" + twoDigitMonth;
            var twoDigitDate = $date.getDate() + "";
            if (twoDigitDate.length == 1)
                twoDigitDate = "0" + twoDigitDate;
            var twoDigitHours = $date.getHours() + "";
            if (twoDigitHours.length == 1)
                twoDigitHours = "0" + twoDigitHours;
            var twoDigitMinutes = $date.getMinutes() + "";
            if (twoDigitMinutes.length == 1)
                twoDigitMinutes = "0" + twoDigitMinutes;
            var $value = twoDigitDate + "." + twoDigitMonth + "." + $date.getFullYear() + " " + twoDigitHours + ":" + twoDigitMinutes; console.log($value);
            console.log($initialVal);
        }
        $(this).datetimepicker({
            format:'d.m.Y H:i',
            lang:'ru',
            mask:true,
            value: $value,
        });
    });
});