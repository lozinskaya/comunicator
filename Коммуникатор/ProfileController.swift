//
//  SettingsViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 05.12.2020.
//

import Foundation
import UIKit

class ProfileController: UIViewController {
    static var current : ProfileController = ProfileController();
    static var showed = false;
    
    @IBAction func ClikExit(_ sender: Any) {
    }
    @IBAction func ClikName(_ sender: Any) {
        let alert = UIAlertController(title: "Изменение имени", message: "Необходимо обратиться к администратору “Коммуникатора”", preferredStyle: UIAlertController.Style.alert)
        alert.addAction(UIAlertAction(title: "Так и сделаю", style: UIAlertAction.Style.default, handler: nil))
        self.present(alert, animated: true, completion: nil)
    }
    @IBAction func ClikEmail(_ sender: Any) {
        let alert = UIAlertController(title: "Изменение E-mail", message: "Необходимо обратиться к администратору “Коммуникатора”", preferredStyle: UIAlertController.Style.alert)
        alert.addAction(UIAlertAction(title: "Так и сделаю", style: UIAlertAction.Style.default, handler: nil))
        self.present(alert, animated: true, completion: nil)
    }
    @IBAction func ClikRules(_ sender: Any) {
        if let url = URL(string: "https://vk.com/page-37741483_44174457") {
            UIApplication.shared.open(url)
        }
    }
    @IBAction func ClikConnectUs(_ sender: Any) {
        let alert = UIAlertController(title: nil, message: nil, preferredStyle: .actionSheet)
            
            alert.addAction(UIAlertAction(title: "Написать в VK", style: .default , handler:{ (UIAlertAction)in
                if let url = URL(string: "https://m.vk.com/write-37741483?mvk_entrypoint=community_page") {
                    UIApplication.shared.open(url)
                }
            }))
            
            alert.addAction(UIAlertAction(title: "+7 (912) 625-46-53", style: .default , handler:{ (UIAlertAction)in
                print("User click number-tel")
            }))

            alert.addAction(UIAlertAction(title: "Отменить", style: .cancel, handler:{ (UIAlertAction)in
                print("User click Отменить")
            }))

            
            //uncomment for iPad Support
            //alert.popoverPresentationController?.sourceView = self.view

            self.present(alert, animated: true, completion: {
                print("completion block")
            })
    }
    
    @IBOutlet weak var balanceLabel: UILabel!
    @IBOutlet weak var nameLabel: UILabel!
    @IBOutlet weak var emailLabel: UILabel!
    func reloadBalance() {
        balanceLabel.text = Global.balance + " ₽";
    }
    
    override func viewDidLoad() {
        ProfileController.current = self;
        ProfileController.showed = true;
        super.viewDidLoad()
        self.navigationController?.navigationBar.setValue(true, forKey: "hidesShadow")
        self.navigationItem.backBarButtonItem = UIBarButtonItem(title: "", style: .plain, target: nil, action: nil)
        nameLabel.text = (Global.userinfo["firstname"] as? String ?? "") + " " + (Global.userinfo["surname"] as? String ?? "");
        reloadBalance();
        emailLabel.text = Global.userinfo["email"] as? String;
    }
    
    
    
    override func viewWillAppear(_ animated: Bool) {

        reloadBalance();
    }

}
