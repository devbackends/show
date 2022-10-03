export const licenseRegion = {
    validate: value => {
        const allowed = [1, 2, 3, 4, 5, 6, 8, 9];
        return allowed.includes(parseInt(value));
    }
};

export const licenseType = {
    validate: value => {
        const allowed = [
            "01", "02", "03", "06",
            "07", "08", "09", "10", "11",
        ];
        return allowed.includes(value);
    }
};

export const licenseExpire = {
    validate: value => {
        const split = value.split('');
        const year = split[0];
        const month = split[1];
        const allowedMonths = [
            'A', 'B', 'C', 'D', 'E',
            'F', 'G', 'H', 'J', 'K',
            'L', 'M',
        ];
        const allowedYears = [
            '0', '1', '2', '3', '4',
            '5', '6', '7', '8', '9',
        ];
        return allowedMonths.includes(month) && allowedYears.includes(year);
    }
};
