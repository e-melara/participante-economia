export function capitalize(word) {
  if (!word) return "";
  return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
}

export function capitalizeSentence(sentence) {
  if (!sentence) return "";
  return sentence
    .split(" ")
    .map(word => capitalize(word))
    .join(" ");
}

