import dayjs from "dayjs";

const Helpers = {
  // Slugify src: https://gist.github.com/codeguy/6684588
  slugify: (text, separator = "-") => {
    return text
      .toString() // Cast to string (optional)
      .normalize("NFKD") // The normalize() using NFKD method returns the Unicode Normalization Form of a given string.
      .replace(/[\u0300-\u036f]/g, "") // remove all previously split accents
      .toLowerCase() // Convert the string to lowercase letters
      .trim() // Remove whitespace from both sides of a string (optional)
      .replace(/\s+/g, separator) // Replace spaces with -
      .replace(/[^\w\-]+/g, "") // Remove all non-word chars
      .replace(/\_/g, separator) // Replace _ with -
      .replace(/\-\-+/g, separator) // Replace multiple - with single -
      .replace(/\-$/g, ""); // Remove trailing -
  },

  filenameValidator: (fname, { replacement = " " } = {}) => {
    // https://stackoverflow.com/a/31976060
    // https://gist.github.com/doctaphred/d01d05291546186941e1b7ddc02034d3

    // resolve multi-line, whitespace trimming
    fname = fname
      .split(/[\r\n]/)
      .map((s) => s.trim())
      .filter((s) => s.length)
      .join(" ");

    // forbidden characters
    // (after multi-line, because new-line-chars are themselves forbidden characters)
    fname = fname.replaceAll(/[<>:"\/\\\|?*\x00-\x1F]/g, replacement);

    // advanced trim
    fname = fname.replace(/\.$/, "");

    // empty filename
    if (!fname.length) {
      fname = "_";
    }

    // forbidden filenames
    if (
      fname.match(
        /^(CON|PRN|AUX|NUL|COM1|COM2|COM3|COM4|COM5|COM6|COM7|COM8|COM9|LPT1|LPT2|LPT3|LPT4|LPT5|LPT6|LPT7|LPT8|LPT9)(\..+)?$/
      )
    ) {
      fname = `_${fname}`;
    }

    return fname;
  },

  // https://stackoverflow.com/a/48593447
  uniqid: (prefix = "", random = false) => {
    const sec = Date.now() * 1000 + Math.random() * 1000;
    const id = sec.toString(16).replace(/\./g, "").padEnd(14, "0");
    return `${prefix}${id}${
      random ? `.${Math.trunc(Math.random() * 100000000)}` : ""
    }`;
  },

  updateModel: (models, updated, addIfNotExists = true, key = "id") => {
    let model = models.find((model) => model[key] == updated[key]);
    if (model) {
      Object.assign(model, updated);
    } else {
      if (addIfNotExists) {
        models.push(updated);
      }
    }
  },

  onRequestError: (error, InputFields) => {
    let errors = error.response?.data?.errors;
    for (const key in errors) {
      InputFields[key].setError(errors[key][0]);
    }
  },

  formatDate: (date, fallback = null, format = "MMMM DD, YYYY") => {
    if (!date) return fallback;
    return dayjs(date).format(format);
  },
  formatFileSize: (sizeInBytes) => {
    if (sizeInBytes < 1024) {
      return sizeInBytes + " bytes";
    } else if (sizeInBytes < 1048576) {
      return (sizeInBytes / 1024).toFixed(2) + " KB";
    } else if (sizeInBytes < 1073741824) {
      return (sizeInBytes / 1048576).toFixed(2) + " MB";
    } else {
      return (sizeInBytes / 1073741824).toFixed(2) + " GB";
    }
  },
};

export default Helpers;
