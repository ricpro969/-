import tkinter as tk
from tkinter import ttk, messagebox
import random
import string
import json
import os


class PasswordGeneratorApp:
    def __init__(self, root):
        self.root = root
        self.root.title("Random Password Generator")
        self.root.geometry("500x500")
        self.history_file = "history.json"

        self.length_var = tk.IntVar(value=12)
        self.use_digits = tk.BooleanVar(value=True)
        self.use_letters = tk.BooleanVar(value=True)
        self.use_special = tk.BooleanVar(value=False)

        self.setup_ui()
        self.load_history()

    def setup_ui(self):
        tk.Label(self.root, text="Длина пароля:").pack(pady=(10, 0))
        tk.Scale(self.root, from_=4, to_=32, orient="horizontal", variable=self.length_var).pack(fill="x", padx=20)

        tk.Checkbutton(self.root, text="Цифры (0-9)", variable=self.use_digits).pack(anchor="w", padx=20)
        tk.Checkbutton(self.root, text="Буквы (a-z, A-Z)", variable=self.use_letters).pack(anchor="w", padx=20)
        tk.Checkbutton(self.root, text="Спецсимволы (!@#$)", variable=self.use_special).pack(anchor="w", padx=20)

        tk.Button(self.root, text="Сгенерировать пароль", command=self.generate_password, bg="#4CAF50",
                  fg="white").pack(pady=10)

        self.result_entry = tk.Entry(self.root, font=("Arial", 14), justify="center")
        self.result_entry.pack(pady=5, fill="x", padx=20)

        tk.Label(self.root, text="История паролей:").pack(pady=(10, 0))
        self.history_tree = ttk.Treeview(self.root, columns=("Password"), show="headings", height=8)
        self.history_tree.heading("Password", text="Пароль")
        self.history_tree.pack(fill="both", expand=True, padx=20, pady=10)

    def generate_password(self):
        chars = ""
        if self.use_digits.get(): chars += string.digits
        if self.use_letters.get(): chars += string.ascii_letters
        if self.use_special.get(): chars += string.punctuation

        if not chars:
            messagebox.showwarning("Ошибка", "Выберите хотя бы один тип символов!")
            return

        length = self.length_var.get()
        password = "".join(random.choice(chars) for _ in range(length))

        self.result_entry.delete(0, tk.END)
        self.result_entry.insert(0, password)
        self.save_to_history(password)

    def save_to_history(self, password):
        self.history_tree.insert("", 0, values=(password,))
        history = self.get_history_from_json()
        history.append(password)
        with open(self.history_file, "w") as f:
            json.dump(history, f)

    def get_history_from_json(self):
        if os.path.exists(self.history_file):
            with open(self.history_file, "r") as f:
                try:
                    return json.load(f)
                except:
                    return []
        return []

    def load_history(self):
        history = self.get_history_from_json()
        for pwd in reversed(history):
            self.history_tree.insert("", 0, values=(pwd,))


if __name__ == "__main__":
    root = tk.Tk()
    app = PasswordGeneratorApp(root)
    root.mainloop()
