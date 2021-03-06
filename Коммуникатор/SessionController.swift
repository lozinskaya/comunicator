//
//  SettingsViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 05.12.2020.
//

import Foundation
import UIKit

extension UIImageView {
    func load(url: URL) {
        DispatchQueue.global().async { [weak self] in
            if let data = try? Data(contentsOf: url) {
                if let image = UIImage(data: data) {
                    DispatchQueue.main.async {
                        self?.image = image
                    }
                }
            }
        }
    }
}

class SessionController: UIViewController {
    
    
    //загруженность кафе
    @IBOutlet weak var busyCafe: UILabel!
    //Статус: начат/не начат сеанс
    @IBOutlet weak var statusSession: UILabel!
    //Совет, что  делать пользователю для начала/завершения сеанса
    @IBOutlet weak var adviceLabel: UILabel!
    //view со временем и количеством людей в сеансе
    @IBOutlet weak var activSessionView: UIView!
    //view с загруженность кафе (показывается, когда сеанс не начат
    @IBOutlet weak var noActivSessionView: UIView!
    //баланс
    @IBOutlet weak var balanceLabel: UILabel!
    @IBOutlet weak var qrView: UIImageView!
    //тарифф
    @IBOutlet weak var tariffLabel: UILabel!
    
    //время сеанса
    @IBOutlet weak var timeLabel: UILabel!
    //количество человек на сеансе
    @IBOutlet weak var countLabel: UILabel!
    
    var timer = Timer()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        qrView.load(url: WebFuncs.ActionUrl(action: "qrcode", params: ["code":Global.userinfo["code"] as! String]));
        timerAction()
        timer = Timer.scheduledTimer(timeInterval: 2,target: self, selector: #selector(timerAction), userInfo: nil, repeats: true)
    }
    
    @objc func timerAction() {
       WebFuncs.JSONRequest(action: "session", params: ["sessionkey": Global.sessionkey]) { result in
            debugPrint(result);
            if let result = result {
                DispatchQueue.main.async {
                    Global.sessioninfo = result;
                    Global.balance = Global.sessioninfo["balance"] as? String ?? "0"
                    let did_active = Global.is_active
                    Global.is_active = Global.sessioninfo["is_active"] as? Int ?? 0
                    self.balanceLabel.text = Global.balance + " ₽"
                    self.activSessionView.isHidden = Global.is_active != 1
                    self.noActivSessionView .isHidden = Global.is_active == 1
                    self.statusSession.text = Global.is_active == 1 ? "Сеанс начат" : "Сеанс не начат";
                    self.adviceLabel.text = Global.is_active == 1 ? "Чтобы завершить сеанс покажите QR-код администратору" : "Чтобы начать сеанс покажите QR-код администратору";
                    if(Global.is_active == 1) {
                        Global.activesession = Global.sessioninfo["active"] as! [String : AnyObject]
                        debugPrint(Global.activesession)
                        self.timeLabel.text = (Global.activesession["duration_min"] as? String ?? "0") + " мин"
                        let extra_count = (Int(Global.activesession["count"] as! String) ?? 0) - 1
                        self.countLabel.text = extra_count == 0 ? "Я" : ("Я и еще " + String(extra_count))
                        self.tariffLabel.text = "1 мин = " + (Global.activesession["tariff_sum"] as? String ?? "1") + " ₽"
                    }
                    else {
                        Global.lastsession = Global.sessioninfo["last"] as? [String : AnyObject] ?? [:]
                        let busy = (Global.sessioninfo["people_count"] as! Int) * 100 / (Global.sessioninfo["max_people"] as! Int)
                        self.busyCafe.text = String(busy) + "%"
                        self.tariffLabel.text = "1 мин = 1 ₽"
                    }
                    
                    if did_active == 1 && Global.is_active == 0 {
                        let storyBoard: UIStoryboard = UIStoryboard(name: "Main", bundle: nil)
                        let finishController = storyBoard.instantiateViewController(withIdentifier: "finishController") as! FinishController;
                        finishController.modalPresentationStyle = .fullScreen;
                                self.present(finishController, animated: true, completion: nil)
                    }
                    
                }
            }
        }
    } 
}
