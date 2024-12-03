import time
import random

class VirtualPet:
    def __init__(self):
        self.happiness = 50
        self.hunger = 50

    def feed(self):
        if self.hunger > 0:
            self.hunger -= 10
            self.happiness -= 2
            print("You fed your pet. Hunger decreased, but happiness slightly decreased.")
        else:
            print("Your pet is not hungry!")

    def play(self):
        if self.happiness < 100:
            self.happiness += 10
            self.hunger += 2
            print("You played with your pet. Happiness increased, but hunger slightly increased.")
        else:
            print("Your pet is already very happy!")

    def check_status(self):
        print(f"Pet's Happiness Level: {self.happiness}")
        print(f"Pet's Hunger Level: {self.hunger}")

    def update_status(self):
        """Simulate the passage of time by decreasing happiness and increasing hunger."""
        self.hunger += random.randint(1, 5)  # Increase hunger randomly
        self.happiness -= random.randint(1, 5)  # Decrease happiness randomly
        self.hunger = min(self.hunger, 100)  # Cap hunger at 100
        self.happiness = max(self.happiness, 0)  # Cap happiness at 0

def main():
    pet = VirtualPet()
    while True:
        print("\n--- Virtual Pet Simulator ---")
        print("1. Feed the pet")
        print("2. Play with the pet")
        print("3. Check pet's status")
        print("4. Quit the game")

        choice = input("Choose an action (1-4): ")

        if choice == '1':
            pet.feed()
        elif choice == '2':
            pet.play()
        elif choice == '3':
            pet.check_status()
        elif choice == '4':
            print("Thank you for playing! Goodbye!")
            break
        else:
            print("Invalid choice. Please try again.")

        pet.update_status()  # Update pet's status based on time

        # Check for game over conditions
        if pet.hunger >= 100:
            print("Your pet has become too hungry! Game Over.")
            break
        if pet.happiness <= 0:
            print("Your pet has become too sad! Game Over.")
            break

if __name__ == "__main__":
    main()